<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SubscriptionRequest;
use App\Http\Requests\Api\CancelSubscriptionRequest;
use App\Http\Requests\Api\SwitchSubscriptionRequest;
use App\Http\Requests\Api\BuySubscriptionRequest;
use App\Repositories\ProfileSubscriptionRepository;
use App\Repositories\SubscriptionPlanRepository;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class SubscriptionController extends Controller
{
    
    /**
    * Function used to get subscription plan
    * @param Request  
    * @return $subscriptionPlan
    */
    public function getSubscriptionPlan(Request $request){
        try {

            $subscriptionPlan = SubscriptionPlanRepository::getSubscriptionPlan(['status'=>'active']);
            $iOsPlan = [];
            if($subscriptionPlan->isNotEmpty()) {
                foreach($subscriptionPlan as $plan) {
                    if(!empty($plan->apple_product_id)) {
                        $iOsPlan[] = $plan->apple_product_id;
                    }
                }
            }
            return response()->json(
                [
                    'success' => true,
                    'data' => ['android' =>$subscriptionPlan,'iosPlan'=>$iOsPlan],
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => "",
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    } 

   /**
    * Function used for subscription checkout. 
    * @param SubscriptionRequest  
    * @return $subscriptionPlan
    */
    public function subscriptionCheckout(SubscriptionRequest $request){
        try {

            $getProfile = ProfileSubscriptionRepository::subscriptionChekout($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getProfile,
                    'message' => __('message.payment_success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => "",
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    } 

   /**
    * Function used to get switch plan.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */
    public function getSwitchPlan(Request $request){
        try {

            $getSwitchPlan = SubscriptionPlanRepository::getSwitchPlan($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getSwitchPlan,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => "",
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
   /**
    * Function used to get buy now plan.
    * @param Request  
    * @return \Illuminate\Http\JsonResponse
    */
    public function getBuyNowPlan(Request $request){
        try {

            $getBuyNowPlan = SubscriptionPlanRepository::getSubscriptionPlan(['status'=>'active'], ['free_trial']);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getBuyNowPlan,
                    'message' => __('message.success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => "",
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
   /**
    * Function is used to cancel profile subscription.
    * @param CancelSubscriptionRequest  
    * @return \Illuminate\Http\JsonResponse
    */
    public function cancelSubscription(CancelSubscriptionRequest $request){
        try {
            ProfileSubscriptionRepository::cancelSubscription($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.subscription_cancel_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    } 

   /**
    * Function is used to switch subscription.
    * @param SwitchSubscriptionRequest
    * @return \Illuminate\Http\JsonResponse 
    */
    public function switchSubscription(SwitchSubscriptionRequest $request){
        try {
            ProfileSubscriptionRepository::switchSubscription($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.subscription_switched_successfully')
                ],
                Response::HTTP_OK
            );
            
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }   
    
    /**
     * Function used to get user transections.
     * @param Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactions(Request $request){
        try {
            $getTransectionList = ProfileSubscriptionRepository::getProfileTransactions($request, '' ,'transection');
            return response()->json(
                [
                    'success' => true,
                    'data' => $getTransectionList,
                    'message' => __('message.success'),
                ],
                Response::HTTP_OK
            );
            
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
    /**
     * Function used to get user subscription list.
     * @param Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubscription(Request $request){
        try {
            $getTransectionList = ProfileSubscriptionRepository::getProfileTransactions($request, 'canceled', 'subscription');
            return response()->json(
                [
                    'success' => true,
                    'data' => $getTransectionList,
                    'message' => __('message.success'),
                ],
                Response::HTTP_OK
            );
            
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
    /**
     * Buy new profile subscription.
     * @param BuySubscriptionRequest $request 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function buySubscription(BuySubscriptionRequest $request){
        try {
            ProfileSubscriptionRepository::buySubscription($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.plan_purchesed_successfully'),
                ],
                Response::HTTP_OK
            );
            
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * save apple Transaction Detail
     * @param saveTransactionDetail $request 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function saveTransactionDetail(Request $request){
        try {
            
            \Log::debug('iOS-subscriptionDetail-new',['data'=>$request->all()]);
            
            $response = ProfileSubscriptionRepository::saveTransactionDetail($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $response,
                    'message' => __('message.plan_purchesed_successfully'),
                ],
                Response::HTTP_OK
            );
            
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
}
