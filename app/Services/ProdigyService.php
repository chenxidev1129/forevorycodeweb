<?php

namespace App\Services;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Models\ProdigiOrderHistory;
use App\Repositories\ProfileRepository;
use App\Jobs\SendEmailJob;

/**
 * Class ProdigyService
 */
class ProdigyService
{
       
    /**
     * Method sendToprodigy
     */
    public static function sendToprodigy($profile_id)
    {   

        /* Get Profile details */
        $getProfile = ProfileRepository::findOne(['id'=>$profile_id,'status'=>'active'], ['user']);

        $reference_id = getReferenceId();
        /* Create prodigy history */
        $prodigy = ProdigiOrderHistory::Create(['profile_id'=> $profile_id, 'reference_id'=> $reference_id]);

        /* Curl to send qr code to prodigy */
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => Config::get('constants.Prodigi.REQUEST_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "merchantReference": "'.$reference_id.'",
                "shippingMethod": "Budget",
                "recipient": {
                    "address": {
                        "line1": "'.$getProfile->user->address.'",
                        "line2": "'.$getProfile->user->address.'",
                        "postalOrZipCode": "'.$getProfile->user->zip_code.'",
                        "countryCode": "'.$getProfile->user->country_short_name.'",
                        "townOrCity": "'.$getProfile->user->city.'",
                        "stateOrCounty": "'.$getProfile->user->state.'"
                    },
                    "name": "'.ucfirst($getProfile->user->first_name.' '.$getProfile->user->last_name).'"
                    
                },
                "items": [
                    {
                        "merchantReference": "item #1",
                        "sku": "M-STI-3X4",
                        "copies": 1,
                        "sizing": "fillPrintArea",
                        "assets": [
                            {
                                "printArea": "Default",
                                "url": "'.Storage::url($getProfile->qrcode_image).'",
                                "md5Hash": "dcb2b27755a6f2ceb09089856508f31b"
                            }
                        ]
                    }
                ],
                "metadata": {
                    "mycustomkey":"some-guid",
                    "someCustomerPreference": {
                        "preference1": "something",
                        "preference2": "red"
                    },
                    "sourceId": 12345
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'X-API-Key:'.Config::get('constants.Prodigi.REQUEST_TOKEN'),
                'Content-Type: application/json'
            ),
        ));

        $responseData = curl_exec($curl);

        if(curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);

        if(!empty($responseData)) {

            $gateWayResponse = json_decode($responseData);
            
            if ($gateWayResponse->outcome == 'Created') {
                /* Update response data */
                $updateData['order_id'] = $gateWayResponse->order->id;
                $updateData['prodigi_response'] = $responseData;
                $updateData['status'] = 'success';

                $updateStatus = ProdigiOrderHistory::where('profile_id',$profile_id)
                    ->where('reference_id',$reference_id)
                    ->update($updateData);
                if(!empty($updateStatus)){
                    SendEmailJob::dispatch(
                        [
                            'email' => $getProfile->user->email
                        ], 
                        [
                            'name' => ucfirst($getProfile->user->first_name.' '.$getProfile->user->last_name),
                            'subjectLine' => __('message.subject_prodigy'),
                            'template' => 'user.email.profile.prodigy'
                        ]
                    );
                    return true;
                }

            } else {
                /* Update response data */
                $updateData['prodigi_response'] = $responseData;
                $updateData['status'] = 'fail';

                ProdigiOrderHistory::where('profile_id',$profile_id)
                    ->where('reference_id',$reference_id)
                    ->update($updateData);

                return false;
            }
        }
    }
}
