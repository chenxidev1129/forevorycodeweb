<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProfileSubscriptionRepository;
use App\Repositories\UserCardRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Validator;


class WebhookController extends Controller
{
    /**
     * Function to get Stripe events.
     * @return \Illuminate\Http\Response 
     */
    public function stripeWebhooks(Request $request) {

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = config('services.stripe.webhook_secret_key');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            Log::debug('invalid.payload', ['response'=>($e->getMessage())]);
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::debug('invalid.signature', ['response'=>($e->getMessage())]);
            http_response_code(400);
            exit();
        }
        
        // Handle the event
        switch ($event->type) {

            case 'customer.created':
                $response = $event->data->object;
            break;

            case 'customer.deleted':
                $response = $event->data->object;
            break;

            case 'customer.updated':
                $event= json_decode( $payload, FALSE );
                $response = $event->data->object;
                $status =  UserCardRepository::webhookCustomerDefaultCard($response);
                Log::debug('customer.updated', ['response'=>($status)]);
            break;

            case 'customer.card.created':
                $response = $event->data->object;
            break;

            case 'customer.card.deleted':
                $response = $event->data->object;
            break;

            case 'customer.source.created':
                $response = $event->data->object;
            break;

            case 'customer.source.deleted':
                $event= json_decode( $payload, FALSE );
                $response = $event->data->object;
                $status =  UserCardRepository::webhookDeleteCard($response);
                Log::debug('customer.source.deleted', ['response'=>($status)]);
            break;

            case 'customer.source.expiring':
                $response = $event->data->object;
            break;
            
            case 'customer.subscription.created':
                $response = $event->data->object;
            break;

            case 'customer.subscription.deleted':
                $event= json_decode( $payload, FALSE );
                $response = $event->data->object;
                ProfileSubscriptionRepository::profileSubscriptionCancel($response);
                Log::debug('customer.subscription.deleted', ['response'=>($response)]);
            break;
            
            case 'customer.subscription.trial_will_end':
                $event= json_decode( $payload, FALSE );
                $response = $event->data->object;
                /* update free trial subscription */
                Log::debug('trial_will_end', ['response'=>($response)]);
            break;
            
            case 'customer.subscription.updated':
                $event= json_decode( $payload, FALSE );
                $response = $event->data->object;
                ProfileSubscriptionRepository::profileSubscriptionServiceStart($response);
               Log::debug('customer.subscription.updated', ['response'=>($response)]);
            break; 
            
            case 'invoice.created':
                $response = $event->data->object;
            break;
            
            case 'invoice.paid':
                $response = $event->data->object;
            break;
            
            case 'invoice.payment_failed':
                $response = $event->data->object;
            break;
            
            case 'invoice.payment_succeeded':
                $response = $event->data->object;
            break;
            
            case 'issuing_card.created':
                $response = $event->data->object;
            break;
            
            case 'issuing_card.updated':
                $response = $event->data->object;
            break;
            
            default:
                echo 'Received unknown event type ' . $event->type;
            break;
        }

        http_response_code(200);

        Log::debug('stripe_events', ['event'=>$event->type]);
    }

    /**
     * Function to get Apple events.
     * @return \Illuminate\Http\Response 
     */
    public function appleWebhooks(Request $request) {
        Log::debug('ios_webhook_new_events-new',['request'=>$request->all()]);
        try {

            $token = (new Parser())->parse($request->signedPayload);

            // if (! (new Validator())->validate($token->headers)) {
            //     throw new \RuntimeException('No way!');
            // }
            
            $token->headers(); // Retrieves the token headers
            $payloadData = $token->claims(); // Retrieves the token claims

        } catch(\InvalidArgumentException $e) {
            // Invalid signature
            Log::debug('invalid.signature', ['response'=>($e->getMessage())]);
            http_response_code(400);
            exit();
        }
        // dd($payloadData);

        if(!empty($payloadData)) {

            $signedTransactionInfo = $payloadData->get('data')->signedTransactionInfo;
            $transactionData = (new Parser())->parse($signedTransactionInfo);

            $subscriptionData = $transactionData->claims();

            $isSubscripted = isSubscripted($subscriptionData);

            if(!empty($isSubscripted)) {
                if($isSubscripted->subscription->slug == 'life_time') {
                    Log::debug('subscription.'.$payloadData->get('notificationType'), ['response'=>($request->signedPayload)]);
                } else {

                    // Handle the event
                    switch ($payloadData->get('notificationType')) {

                        case 'SUBSCRIBED':

                            ProfileSubscriptionRepository::profileSubscriptionPurchaseIos($subscriptionData);
                            Log::debug('subscription.SUBSCRIBED', ['response'=>($request->signedPayload)]);

                        break;
                        
                        case 'DID_RENEW':

                            ProfileSubscriptionRepository::profileSubscriptionRenewIos($subscriptionData);
                            Log::debug('subscription.DID_RENEW', ['response'=>($request->signedPayload)]);

                        break;

                        case 'DID_FAIL_TO_RENEW':

                            ProfileSubscriptionRepository::profileSubscriptionRenewFailIos($subscriptionData);
                            Log::debug('subscription.DID_FAIL_TO_RENEW', ['response'=>($request->signedPayload)]);

                        break;

                        case 'DID_CHANGE_RENEWAL_STATUS':
                            
                            if($payloadData->get('subtype') == 'AUTO_RENEW_DISABLED') {
                                ProfileSubscriptionRepository::profileSubscriptionCancelIos($subscriptionData,'expired');
                            }
                            elseif($payloadData->get('subtype') == 'AUTO_RENEW_ENABLED') {
                                /* send notification to customer for enabled renew subscriptions */
                                ProfileSubscriptionRepository::profileSubscriptionEnableIos($subscriptionData);
                            }
                            Log::debug('subscription.DID_CHANGE_RENEWAL_STATUS', ['response'=>($request->signedPayload)]);

                        break;

                        case 'DID_CHANGE_RENEWAL_PREF': //autoRenewProductId with switch plan id in downgrade
                            
                            $signedRenewalInfo = $payloadData->get('data')->signedRenewalInfo;
                            $renewalData = (new Parser())->parse($signedRenewalInfo);
                            $switchData = $renewalData->claims();

                            ProfileSubscriptionRepository::profileSubscriptionSwitchIos($subscriptionData,$switchData);
                            Log::debug('subscription.DID_CHANGE_RENEWAL_PREF', ['response'=>($request->signedPayload)]);

                        break;

                        case 'EXPIRED':
                            
                            ProfileSubscriptionRepository::profileSubscriptionCancelIos($subscriptionData,'canceled');
                            Log::debug('subscription.EXPIRED', ['response'=>($request->signedPayload)]);

                        break;
                        
                        default:
                            echo 'Received unknown event type ' . $payloadData->get('notificationType');
                        break;
                    }
                }
            }

            http_response_code(200);
            Log::debug('ios_webhook_events',['event'=>$payloadData->get('notificationType')]);
        }
    }
    
}
