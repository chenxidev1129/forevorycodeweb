<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Requests\AddCardRequest;
use App\Repositories\ProfileSubscriptionRepository;
use App\Repositories\SubscriptionPlanRepository;
use App\Repositories\UserCardRepository;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class SubscriptionController extends Controller
{

   /**
    * Function used to get subscription plan price.
    * @param Request $request 
    * @return \Illuminate\Http\JsonResponse 
    */
    
    public function getSubscriptionPlanPrice(Request $request){
        try {
            
            $getPrice = SubscriptionPlanRepository::getSubscriptionPlanPrice($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getPrice,
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
    * Function used for plan checkout.
    * @param SubscriptionRequest $request 
    * @return \Illuminate\Http\JsonResponse 
    */
    public function getSubscription(SubscriptionRequest $request){
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
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    } 

   /**
    * Cancel subscription.
    * @param Request $request 
    * @return \Illuminate\Http\JsonResponse 
    */
    public function cancelSubscription(Request $request){
        try {
            $result = ProfileSubscriptionRepository::cancelSubscription($request);
            if($result) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => __('message.subscription_cancel_successfully')
                    ],
                    Response::HTTP_OK
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => __('message.subscription_cancel_failed')
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
            
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
    * Switch subscription.
    * @param Request $request 
    * @return \Illuminate\Http\JsonResponse 
    */
    public function switchSubscription(Request $request){
        try {
            $message = ProfileSubscriptionRepository::switchSubscription($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => [],
                    'message' => $message,
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
    * Function used to show checkout detail.
    * @return \Illuminate\View\View
    */
    public function checkoutDetail(Request $request){
     try{      
            $getUserSelectPlan =  SubscriptionPlanRepository::findOne(['id' => $request->planId]);
            $getUserSavedCard = UserCardRepository::getSaveCard();
            return view('user.checkout', ['getUserSelectPlan'=> $getUserSelectPlan, 'getUserSavedCard'=> $getUserSavedCard, 'subscriptionId' => $request->subscriptionId, 'type' => $request->type]);
        }catch(\Exception $ex){
            throw $ex;
        }   
    }   

   /**
    * Buy new subscription.
    * @param Request $request 
    * @return \Illuminate\Http\JsonResponse 
    */
    public function buySubscription(Request $request){
        try {
            $profileId = ProfileSubscriptionRepository::buySubscription($request);
            return response()->json(
                [
                    'success' => true,
                    'profileId' => $profileId,
                    'message' => __('message.plan_purchesed_successfully'),
                ],
                Response::HTTP_OK
            );
            
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'profileId' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }  
 
   /**
    * Add user card
    * @param AddCardRequest $request 
    * @return \Illuminate\Http\JsonResponse 
    */
    public function addCard(AddCardRequest $request){
        try {
            UserCardRepository::addNewCard($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.card_added_successfully'),
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
    * Make card as default
    * @param AddCardRequest $request 
    * @return \Illuminate\Http\JsonResponse 
    */
    public function makeCardDefault(Request $request){
        try {
            UserCardRepository::makeCardDefault($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.set_card_default_success'),
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
    * Delete user card
    * @param AddCardRequest $request 
    * @return \Illuminate\Http\JsonResponse 
    */
    public function deleteCard(Request $request){
        try {
            UserCardRepository::deleteCard($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.card_delete_success'),
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
