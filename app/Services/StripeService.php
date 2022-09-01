<?php

namespace App\Services;

use App\Exceptions\StripeException;
use Exception;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\CardException;
use Stripe\StripeClient;
/**
 * Class StripeService
 * 
 * @package App\Gateways\Payments
 */
class StripeService
{
       
    /**
     * Method createCustomer
     *
     * @param array $request [explicite description]
     *
     * @return mixed
     */
    public static function createCustomer($request)
    {   
        try{
            $stripe = new StripeClient(config('services.stripe.secret_key'));
            $customer = $stripe->customers->create(
                [
                    'email' => $request['email']
                ]
            );
            return $customer['id'];
        }catch (Exception $exception){
            throw new StripeException($exception->getMessage());
        }
    }


    /**
     * Method getCustomer
     *
     * @param array $request [explicite description]
     *
     * @return mixed
     */
    public static function getCustomer($request)
    {   
        try{
            $stripe = new StripeClient(config('services.stripe.secret_key'));
            $customer = $stripe->customers->retrieve(
                $request['customer_id'],
                []
              );
            return $customer;
        }catch (Exception $exception){
            throw new StripeException($exception->getMessage());
        }
    }    
       
    /**
     * Method saveCard
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function saveCard($request)
    {   
        try{
            $stripe = new StripeClient(config('services.stripe.secret_key'));
            if (isset($request['token']) && !empty($request['token'])) {
                $tokenId = $request['token'];
            } else {
                $tokenId = self::getToken($request);
            }
            
            $card = $stripe->customers
                ->createSource($request['customer_id'], ['source' => $tokenId]);

            return $card;
            
        }catch (\Exception $exception){
            throw new StripeException($exception->getMessage());
        }
    }
    
    /**
     * Method removeCard
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public static function removeCard($request)
    {
      
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return  $stripe->customers->deleteSource(
            $request['customer_id'],
            $request['card_id'],
            []
        );
    }

        
    /**
     * Method getToken
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function getToken($request)
    {
        try{
            $stripe = new StripeClient(config('services.stripe.secret_key'));
            $token = $stripe->tokens->create(
                [
                    'card' => [
                        'number'            => $request['card_number'],
                        'exp_month'         => $request['exp_month'],
                        'cvc'               => $request['card_cvv'],
                        'exp_year'          => $request['exp_year'],
                        'name'              => $request['card_holder'],
                        'address_line1'     => $request['address'],
                        'address_line2'     => $request['address'],
                        'address_city'      => $request['city'],
                        'address_state'     => $request['state'],
                        'address_zip'       => $request['zip_code'],
                        'address_country'   => $request['country'],
                    ],
                ]
            );
            return $token['id'];
        }catch (\Exception $exception){
            throw new StripeException($exception->getMessage());
        }
    }

        
    /**
     * Method createSubscription free trial
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function createSubscription($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return $stripe->subscriptions->create(
            [
                'customer' => $request['customer_id'],
                'items' => [
                    [
                        'price' => $request['stripe_plan_id']
                    ],
                ],
                'trial_period_days' => $request['trial_days'],
                'default_source' => $request['card_id']
            ]
        );
    }

        
    /**
     * Method setDefaultCard
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function setDefaultCard($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return $stripe->customers->update(
            $request['customer_id'],
            [
                'default_source' => $request['token']
            ]
        );
    }

        
    /**
     * Method updateSubscription
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function updateSubscription($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return $stripe->subscriptions->update(
            $request['subscription_id'], ['cancel_at_period_end' => true]
        );
    }

    /**
     * Method cancelSubscription
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function cancelSubscription($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return $stripe->subscriptions->cancel(
            $request['subscription_id'], []
        );
    }


    /**
     * Method updateCanceledSubscription
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function updateCanceledSubscription($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return $stripe->subscriptions->update(
            $request['subscription_id'], ['cancel_at_period_end' => false]
        );
    }

    /**
     * Method switchSubscription
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function switchSubscription($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));

        return $stripe->subscriptionItems->update(
            $request['subscription_item_id'], 
            ['price' => $request['stripe_price_id']]
        );
    }    

    /**
     * Method retrieveCard
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function retrieveCard($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return $stripe->customers->retrieveSource(
            $request['customer_id'],
            $request['card_id'],
            []
        );
    }  


    /**
     * Method Retrieve a Subscription
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function retrieveSubscription($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return $stripe->subscriptions->retrieve(
            $request['subscription_id'],
            []
        );
    }  


    /**
     * Method schedule a Subscription
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function scheduleSubscription($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        
        return $stripe->subscriptionSchedules->create(
            [
                'customer' => $request['customer_id'],
                'start_date' => $request['start_date'],
                'end_behavior' => 'release',
                'phases' => [
                    [
                        'items' => [
                            [
                                'price' => $request['stripe_price_id'],
                            ],
                        ],
                        //'default_source' => $request['card_id']
                    ],
                ],
        
            ]
        );
    }

    /**
     * Method retrieve a Subscription
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function retrieveScheduleSubscription($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return $stripe->subscriptionSchedules->retrieve(
            $request['subscription_schedule_id'],
            []
          );
    }

    /**
     * Method createSubscription without free free trial
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function createSubscriptionWithoutFreeTrail($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return $stripe->subscriptions->create(
            [
                'customer' => $request['customer_id'],
                'items' => [
                    [
                        'price' => $request['stripe_plan_id']
                    ],
                ],
                //'trial_period_days' => $request['trial_days'],
                'default_source' => $request['card_id']
            ]
        );
    }


    /**
     * Method create payment for lifetime subscription without free trail
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function createLifeTimeSubscription($request)
    {

        $stripe = new StripeClient(config('services.stripe.secret_key'));
        return $stripe->charges->create([
            "amount" => $request['price']*100,
            "currency" => config('services.stripe.currency'),
            'customer' => $request['customer_id'],
            "source" => $request['card_id'],
            "description" => "Payment for life time subscription", 
            'shipping' => [
                'name' => $request['name'],
                'address' => [
                  'line1' => $request['country'],
                  'city' => $request['city'],
                  'state' => $request['state'],
                  'country' => $request['country_short_name'],
                  'postal_code' => $request['postal_code'],
                ],
            ],
        ]);
    }    


    /**
     * Method to create a product price
     *
     * @param $request $request [explicite description]
     *
     * @return void
     */
    public static function createPrice($request)
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        
        $price = $stripe->prices->retrieve(
                $request['price_id'],
                []
            );

        if(!empty($price)) {
            
            return $stripe->prices->create(
                [
                    'unit_amount' => $request['amount']*100,
                    'currency' => $price->currency,
                    'recurring' => ['interval' => $price->recurring->interval],
                    'product' => $price->product,
            
                ]
            );
        }

    }
   
}
