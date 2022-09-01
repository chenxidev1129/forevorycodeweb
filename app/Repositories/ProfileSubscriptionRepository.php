<?php

namespace App\Repositories;

use App\Models\ProfileSubscription;
use App\Models\Profile;
use App\Models\UserCard;
use App\Models\Notification;
use App\Services\StripeService;
use App\Repositories\UserCardRepository;
use App\Repositories\ProfileRepository;
use App\Services\ProdigyService;
use App\Jobs\SendPushNotificationJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Exception;


class ProfileSubscriptionRepository{

   /**
    * Find one
    * @param $where
    * @param $with
    * @return array
    */
    public static function findOne($where, $with = [])
    {
        return ProfileSubscription::with($with)->where($where)->first();
    }
   

   /**
    * Find Subscription Status
    * @param int $profileId
    * @return string
    */
    public static function getSubscriptionStatus($profileId)
    {
        $status = 'NA';
        $activeProfile = ProfileRepository::findOne(['id' => $profileId]);  //::where('profile_id',$profileId)->where('status','active')->first();
        if(!empty($activeProfile)) {
            
            $status = $activeProfile->status;
        }else {
            $expiredProfile = ProfileSubscription::where('profile_id',$profileId)->whereIn('status',['inactive','expired'])->first();
            if(!empty($expiredProfile)) {
                $status = 'expired';
            }
        }
        return $status;
    }

   /**
    * Schedule subscription to cancel.
    * @param Request $request 
    * @return Boolean
    */
    public static function cancelSubscription($request){

        $isSubscriptionExist = ProfileSubscription::select('id', 'subscription_id', 'profile_id')->with(['profile:id,user_id,profile_name','profile.user:id,first_name,last_name'])->where(['id'=>$request->id,'status'=>'active'])->first(); 

        if(!empty($isSubscriptionExist)) {
            $subscriptionRequest['subscription_id'] = $isSubscriptionExist->subscription_id;
            /* Check for exist subscription */
            $subscription = StripeService::retrieveSubscription($subscriptionRequest);
           
            if(!empty($subscription['id']) && $subscription['status'] != 'canceled'){
                $result = StripeService::updateSubscription($subscriptionRequest);
                
                if(!empty($result['status']) && $result['cancel_at_period_end'] == 1) {
                    $adminUser = getAdmin();
                    /* Save notification  */
                    $notifaction =
                        [
                            'user_id' => $adminUser->id,
                            'profile_id' => $isSubscriptionExist->profile->id,
                            'title' => "Account Alert",
                            'message' => "".ucwords($isSubscriptionExist->profile->user->first_name.' '.$isSubscriptionExist->profile->user->last_name)." has cancelled a subscription.",
                            'type' => 'cancelled'
                        ];

                   
                    $result = Notification::create($notifaction);

                    ProfileSubscription::where('id',$request->id)->update(['stripe_status'=>'canceled', 'canceled_by'=> 'user']);
                    return true;    
                }

            }
        }  
        
        throw new Exception(__('message.no_active_plan_found_to_cancel'));
    }

   /**
    * Function is used to switch profile subscription to life time plan.
    * @param Request $currentPlan, $switchPlan, $userDetails
    * @return Boolean
    */
    public static function switchToLifeTimeSubscription($currentPlan, $switchPlan, $userDetails, $currentSubscription='', $userSelectedCard=''){
        try{
            
            $subscriptionCheckout = StripeService::createLifeTimeSubscription([
                'price' => $switchPlan->price,
                'customer_id' => $userDetails->customer_id,
                'card_id' => !empty($currentPlan->userCard) ? $currentPlan->userCard->card_id : $userSelectedCard->card_id,
                'name' => !empty($currentPlan->userCard) ? $currentPlan->userCard->card_name : $userSelectedCard->card_name,
                'address' => $userDetails->country,
                'country' => $userDetails->country,
                'city' => $userDetails->city,
                'state' => $userDetails->state,
                'postal_code' => $userDetails->zip_code,
                'country_short_name' => $userDetails->country_short_name
            ]);
    
            $getSwitchSubscription =  ProfileSubscription::create([
                'stripe_charge_id'=> $subscriptionCheckout->id, 
                'profile_id' => $currentPlan->profile_id,
                'card_id' => !empty($currentPlan->userCard) ? $currentPlan->userCard->id : $userSelectedCard->id,
                'plan_id'=> $switchPlan->id, 
                'subscription_price'=> $switchPlan->price, 
                'purchase_plan_id'=> $switchPlan->id, 
                'start_date'=> date('Y-m-d H:i:s'), 
                'end_date'=> NULL
            ]);
    
            if(!empty($getSwitchSubscription)){
            
                /* Cancel the current plan on successfull new subscription */
                if(!empty($currentSubscription) && in_array($currentSubscription['status'], ['active', 'past_due'])){
                    StripeService::cancelSubscription([
                        'subscription_id'=> $currentPlan->subscription_id
                    ]);
                }else{
                      /* Set current plan to canceled */
                    if(!empty($currentSubscription)){
                      
                        ProfileSubscription::where(['subscription_id' => $currentSubscription->id])->update(['start_date'=>  date('Y-m-d H:i:s',$currentSubscription->current_period_start), 'end_date'=> date('Y-m-d H:i:s',$currentSubscription->current_period_end),  'status' => 'canceled', 'stripe_status'=> 'canceled']);

                    }   
                }

                return true;
            }
            
            return false;
            
        }catch(Exception $ex){ 
            
            /* Resume canceled subscription */
            if(!empty($currentSubscription) && !empty($currentSubscription['cancel_at_period_end'])){
                StripeService::updateCanceledSubscription([
                    'subscription_id'=> $currentPlan->subscription_id
                ]);
            }

            throw $ex;
        }
    }

   /**
    * Function is used to switch profile subscription.
    * @param Request $request 
    * @return string
    */
    public static function switchSubscription($request){
        DB::beginTransaction();
        try{
            $post = $request->all();

            $adminUser = getAdmin();

            /* Get login user detail */
            $getUserDetail = getUserDetail();
            $currentPlan = ProfileSubscription::select('id', 'subscription_id', 'profile_id', 'card_id')->where(['id'=>$post['id'],'status'=>'active'])->with(['userCard:id,user_id,card_id,card_name','userCard.user:id,first_name,last_name'])->first();
           
            if(empty($currentPlan) || empty($currentPlan->userCard)) {
                throw new Exception(__('message.card_detail_not_found'));
            }

            $switchPlan = SubscriptionPlanRepository::findOne(['id'=>$post['plan_id']]);
            
            if(empty($switchPlan) || $switchPlan->slug == 'free_trial') {
                throw new Exception(__('message.plan_does_not_exist'));
            }
                
            /* Check for exist subscription */
            $subscription = StripeService::retrieveSubscription(['subscription_id'=> $currentPlan->subscription_id]);
            
            if($subscription['status'] == 'trialing') {

                if($switchPlan->slug == 'life_time'){
                    
                    /* Cancelled current subscription */
                    ProfileSubscription::where('id', $currentPlan->id)->update([
                        'status'=> 'canceled', 
                        'stripe_status'=>'canceled'
                    ]);
                    
                    /* Subscription scheduled to cancel at period end */
                    $cancelAtPeriodEnd = StripeService::updateSubscription([
                        'subscription_id'=> $currentPlan->subscription_id
                    ]);

                    if(!empty($cancelAtPeriodEnd)  && !empty($cancelAtPeriodEnd['cancel_at_period_end'])) { 
                        
                        /* Switch to new plan */
                        $getSwitchStatus = self::switchToLifeTimeSubscription($currentPlan, $switchPlan, $getUserDetail, $subscription);
                        
                        if(!empty($getSwitchStatus)){

                            /* Send QR to prodigy */
                            $getProfile = ProfileRepository::findOne(['id'=>$currentPlan->profile_id]);
                            $getProfile->status = 'active';
                            if($getProfile->prodigy_status =='0'){
                                $prodigyStatus = ProdigyService::sendToprodigy($currentPlan->profile_id);
                                if(!empty($prodigyStatus)){
                                    /* Update prodigy status in respective profile */
                                    $getProfile->prodigy_status = '1'; 
                                }

                            }
                            $getProfile->save();
                        
                            /* Save notification  */
                            $notifaction =
                                [
                                    'user_id' => $adminUser->id,
                                    'profile_id' => $currentPlan->profile_id,
                                    'title' => "Account Alert",
                                    'message' => "".ucwords($currentPlan->userCard->user->first_name.' '.$currentPlan->userCard->user->last_name) ." has upgraded their subscription to ".strtolower($switchPlan->plan).".",
                                    'type' => 'upgrade'
                                ];

                            $result = Notification::create($notifaction);

                            DB::commit();
                            return __('message.plan_switch_success');
                        }
                        
                    }
                    
                    DB::rollback();
                    return false;

                }else{
                    
                    $switchSubscriptionResult = StripeService::switchSubscription(
                        [
                        'subscription_item_id'=> $subscription->items->data[0]->id,
                        'stripe_price_id'=> $switchPlan->stripe_price_id
                        ]
                    );

                    if(!empty($switchSubscriptionResult->id)) {
                        ProfileSubscription::where('id',$post['id'])->update(
                            [
                            'purchase_plan_id'   => $switchPlan->id,
                            'subscription_price' => $switchPlan->price
                            ]
                        );

                        /* Save notification  */
                        $notifaction =
                            [
                                'user_id' => $adminUser->id,
                                'profile_id' => $currentPlan->profile_id,
                                'title' => "Account Alert",
                                'message' => "".ucwords($currentPlan->userCard->user->first_name.' '.$currentPlan->userCard->user->last_name) ." has upgraded their subscription to ".strtolower($switchPlan->plan).".",
                                'type' => 'upgrade'
                            ];

                        $result = Notification::create($notifaction);
                        DB::commit();
                        return __('message.plan_switch_success');

                    }

                    DB::rollback();
                    return false;
                }

            }elseif($subscription['status'] == 'active') {
                /* User want to switch plan during the active paln */
                ProfileSubscription::where('id',$post['id'])->update(['status'=> 'canceled', 'stripe_status'=>'canceled']);

                /* Subscription scheduled to cancel at period end */
                $cancelAtPeriodEnd = StripeService::updateSubscription(
                    [
                    'subscription_id'=> $currentPlan->subscription_id
                    ]
                );

                if(!empty($cancelAtPeriodEnd)  && !empty($cancelAtPeriodEnd['cancel_at_period_end'])) {     
                    
                    if($switchPlan->slug == 'life_time'){
                        $getSwitchPlanStatus = self::switchToLifeTimeSubscription($currentPlan, $switchPlan, $getUserDetail, $subscription);
                        
                        if(!empty($getSwitchPlanStatus)){
                            
                            /* Save notification  */
                            $notifaction =
                                [
                                    'user_id' => $adminUser->id,
                                    'profile_id' => $currentPlan->profile_id,
                                    'title' => "Account Alert",
                                    'message' => "".ucwords($currentPlan->userCard->user->first_name.' '.$currentPlan->userCard->user->last_name) ." has upgraded their subscription to ".strtolower($switchPlan->plan).".",
                                    'type' => 'upgrade'
                                ];
                           
                            $result = Notification::create($notifaction);
                            DB::commit();
                            return __('message.plan_switch_success');

                        }
                        
                        DB::rollback();
                        return false;

                    }else{
                        
                        /* Create new subscriotion of selected plan */
                        $getSwitchPlanStatus = self::createSubscriptionWithoutFreeTrail($currentPlan, $switchPlan, $getUserDetail, $subscription);
                        
                        if(!empty($getSwitchPlanStatus)){

                            /* Save notification  */
                            $notifaction =
                                [
                                    'user_id' => $adminUser->id,
                                    'profile_id' => $currentPlan->profile_id,
                                    'title' => "Account Alert",
                                    'message' => "".ucwords($currentPlan->userCard->user->first_name.' '.$currentPlan->userCard->user->last_name) ." has upgraded their subscription to ".strtolower($switchPlan->plan).".",
                                    'type' => 'upgrade'
                                ];
                           
                            $result = Notification::create($notifaction);
                            DB::commit();
                            return __('message.plan_switch_success');
                        }
                        
                        DB::rollback();
                        return false;
                        
                    } 

                }

                DB::rollback();
                throw new Exception(__('message.something_went_wrong_while_cancel_current_plan'));
            }

        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        } 
    }   
   
   /**
    * Function is used to get new subscription without free trial
    * @param Request $currentPlan, $switchPlan, $userDetail
    * @return Boolean
    */    
    public static function createSubscriptionWithoutFreeTrail($currentPlan, $switchPlan, $userDetail ,$currentSubscription, $userSelectedCard=''){
        
        try{
            /* Create new subscription of selected plan */
            $createStripeSubscription =  StripeService::createSubscriptionWithoutFreeTrail([
                'card_id'=> !empty($currentPlan->userCard) ? $currentPlan->userCard->card_id : $userSelectedCard->card_id,
                'customer_id'=> $userDetail->customer_id, 
                'stripe_plan_id'=> $switchPlan->stripe_price_id
            ]);
                         
            $getSwitchSubscription = ProfileSubscription::create([
                'profile_id' => $currentPlan->profile_id,
                'card_id' => !empty($currentPlan->userCard) ? $currentPlan->userCard->id : $userSelectedCard->id,
                'subscription_id' => $createStripeSubscription['id'],
                'plan_id' => $switchPlan->id,
                'purchase_plan_id' => $switchPlan->id,
                'subscription_price' => $switchPlan->price,
                'start_date' => date('Y-m-d H:i:s',$createStripeSubscription['current_period_start']),
                'end_date' => date('Y-m-d H:i:s',$createStripeSubscription['current_period_end']),
            ]);
            
            if(!empty($getSwitchSubscription)){
                
                /* Cancel the current plan on successfull new subscription */
                if(!empty($currentSubscription) && in_array($currentSubscription['status'], ['active', 'past_due'])){
                    StripeService::cancelSubscription([
                        'subscription_id'=> $currentPlan->subscription_id
                    ]);
                }else{
                      /* Set current plan to canceled */
                    if(!empty($currentSubscription)){
                      
                        ProfileSubscription::where(['subscription_id' => $currentSubscription->id])->update(['start_date'=>  date('Y-m-d H:i:s',$currentSubscription->current_period_start), 'end_date'=> date('Y-m-d H:i:s',$currentSubscription->current_period_end),  'status' => 'canceled', 'stripe_status'=> 'canceled']);

                    }   
                }

                return true;
            }

            return false;

        }catch(Exception $ex){
   
            /* Resume canceled subscription */
            if(!empty($currentSubscription) && !empty($currentSubscription['cancel_at_period_end'])){
                StripeService::updateCanceledSubscription([
                    'subscription_id'=> $currentPlan->subscription_id
                ]);
            }

            throw $ex;
        }
    }

   /**
    * Buy new subscription.
    * @param Request $request 
    * @return json
    */
    public static function buySubscription($request){
        DB::beginTransaction();
        try{
            $post = $request->all();
            /* Get login user detail */
            $getUserDetail = getUserDetail();

            $currentPlan = ProfileSubscription::where('id',$post['id'])->where(function ($query) {
                $query->where('status', 'active')
                    ->orWhere('status', 'expired');
            })->first();
           
            if(!empty($currentPlan)) {

                $userSavedCard = UserCard::where('id', $post['card_id'])->first();
        
                if(empty($userSavedCard)){
                    throw new Exception(__('message.card_detail_not_found'));
                }
                
                /* Get plan using plan id */
                $switchPlan = SubscriptionPlanRepository::findOne(['id'=>$post['plan_id']]);
            
                if(!empty($switchPlan) && $switchPlan->slug != 'free_trial') {

                    /* Check for exist subscription */
                    $subscription = StripeService::retrieveSubscription(['subscription_id' => $currentPlan->subscription_id]);

                    ProfileSubscription::where('id',$post['id'])->update([
                        'status'=> 'canceled', 
                        'stripe_status'=>'canceled'
                    ]);
                    
                    if($switchPlan->slug == 'life_time'){

                        $getLifeTimePlanStatus = self::switchToLifeTimeSubscription($currentPlan, $switchPlan, $getUserDetail, $subscription, $userSavedCard);
                                
                        if(empty($getLifeTimePlanStatus)){
                            DB::rollBack();
                        }

                    }else{

                        /* Create new subscriotion of selected plan */
                        $getBuySubscriptionStatus = self::createSubscriptionWithoutFreeTrail($currentPlan, $switchPlan, $getUserDetail, $subscription, $userSavedCard);
                            
                        if(empty($getBuySubscriptionStatus)){
                            DB::rollBack();
                        }

                    }

                    /* Update profile status */
                    // $getProfileUpdateResult = ProfileRepository::updateProfileStatus([
                    //     'id'=> $currentPlan->profile_id, 
                    //     'status'=> 'active'
                    // ]);
                    
                    /*  Update profile status and Send QR to prodigy */
                    $getProfile = ProfileRepository::findOne(['id'=>$currentPlan->profile_id]);
                    $getProfile->status = 'active';
                    if($getProfile->prodigy_status =='0'){
                        $prodigyStatus = ProdigyService::sendToprodigy($currentPlan->profile_id);
                        if(!empty($prodigyStatus)){
                            /* Update prodigy status in respective profile */
                            $getProfile->prodigy_status = '1'; 
                        }

                    }
                    $getProfile->save();


                    if(empty($getProfileUpdateResult)){
                        DB::rollBack();
                        throw new Exception(__('message.something_went_wrong_while_updating_profile_status'));
                    }

                    DB::commit();
                    return $currentPlan->profile_id;
                }
            }

            throw new Exception(__('message.subscription_plan_not_found'));

        } catch (\Exception $ex) {
            throw $ex;
        } 
    } 

   /**
    * Subscription checkout.
    * @param Request $request 
    *
    */
    public static function subscriptionChekout($request){ 
        try{
           
            $getUser = getUserDetail();
            $getPlan = SubscriptionPlanRepository::findOne(['id' => $request['subscription_id']]);
            $free_trial = SubscriptionPlanRepository::findOne(['slug'=>'free_trial']);
          
            if(!empty($request['exp_date'])){
                $exp_date = $request['exp_date'];
                $explodeExpDate = explode("/", $exp_date);
                $request['exp_month'] = $explodeExpDate[0];
                $request['exp_year'] = $explodeExpDate[1];
            }
           
            if(empty($getPlan)){
                throw new Exception(__('message.subscription_plan_not_found'));
            }

            if($request['card_type'] == 'addNewCard'){
                $getPlan['trial_days'] = $free_trial->days;
                $addCardResponse = self::addCardSubscription($request ,$getPlan);
                return  $addCardResponse;

            }else{
                /* Check for api user */
                if(!empty($request['card_token'])){
                    $getUserCardDetail = UserCardRepository::findOne(['user_id' => $getUser->id, 'card_id'=> $request['card_token']]);
                }else{
                 /* Check for web user */    
                    $getUserCardDetail = UserCardRepository::findOne(['user_id' => $getUser->id, 'id'=> $request['card_type']]);
                }

                if(empty($getUserCardDetail)){
                    throw new Exception(__('message.invalid_card_detail'));
                }

                $getPlan['trial_days'] = $free_trial->days;
                $saveCardResponse = self::saveCardSubscription($getPlan, $getUserCardDetail);
                return $saveCardResponse;
            }
        }catch(\Exception $ex){
                throw $ex;
        }
    } 
    
   /**
    * Subscription payment using save card.
    * @param Request $planRequest, $userCardDetail
    *
    */
    public static function saveCardSubscription($plan, $userCardDetail){ 
        try{
            $getUser = getUserDetail();

            /* Plan is not life time */ 
            if(!empty($plan) && ($plan->slug !== 'life_time')){
               
                /* Create subscription into strip */
                $createSubscription = StripeService::createSubscription([
                    'customer_id'=> $getUser->customer_id,
                    'card_id'=> $userCardDetail->card_id,
                    'stripe_plan_id'=> $plan->stripe_price_id,
                    'trial_days'=> $plan->trial_days,
                ]);
                
                if(empty($createSubscription)){
                    throw new Exception(__('message.something_went_wrong_while_create_subscription'));
                }

                /* Create subscription into forevory */  
                $getSubscription =  self::createSubscription([
                    'user_id'=> $getUser->id,
                    'plan_id'=> $plan->id,
                    'plan_price'=> $plan->price,
                    'card_id'=> $userCardDetail->id
                ],
                [
                    'id'=> $createSubscription['id'],
                    'trial_start'=> $createSubscription['trial_start'],
                    'trial_end'=> $createSubscription['trial_end'],
                    'current_period_start'=> $createSubscription['current_period_start'],
                    'current_period_end'=> $createSubscription['current_period_end'],
                ]);

            }else{

                $subscriptionCheckout = StripeService::createLifeTimeSubscription([ 
                    'price' => $plan->price,
                    'customer_id' => $getUser->customer_id,
                    'card_id' => $userCardDetail->card_id,
                    'name' => $userCardDetail->card_name,
                    'address' => $getUser->country,
                    'country' => $getUser->country,
                    'city' => $getUser->city,
                    'state' => $getUser->state,
                    'postal_code' => $getUser->zip_code,
                    'country_short_name' => $getUser->country_short_name
                ]);

                if(empty($subscriptionCheckout)){
                    throw new Exception(__('message.something_went_wrong_while_create_subscription'));
                }
                
                /* Add subscription checkout info.  */  
                $getSubscription =  self::createLifeTimeSubscription([
                    'user_id'=> $getUser->id,
                    'plan_id'=> $plan->id,
                    'plan_price'=>  $plan->price,
                    'card_id'=> $userCardDetail->id 
                ],
                [
                    'id' => $subscriptionCheckout['id']
                ]);
            }
            
            if(empty($getSubscription)){
                throw new Exception(__('message.something_went_wrong_while_create_subscription'));
            }
                
            return $getSubscription;

        }catch(\Exception $ex){
                throw $ex;
        }
    } 

   /**
    * Subscription payment using using card and save the card
    * @param $getUserCard, $request
    *
    */
    public static function addCardSubscription($request, $getPlan){ 
        try{
            $getUser = getUserDetail();
    
            if(!empty($getUser->customer_id)){

                /* Get token from api request */ 
                $createCardToken = !empty($request['card_token']) ? $request['card_token'] : StripeService::getToken($request);
              
                if(empty($createCardToken)){
                    throw new Exception(__('message.something_went_wrong_create_card'));
                }
            
                /* Check card status  */
                $cardSatatus = self::createCustomerCard(['token'=> $createCardToken, 'user_id'=> $getUser->id, 'customer_id'=> $getUser->customer_id], $request);
              
                if(empty($cardSatatus)){
                    throw new Exception(__('message.something_went_wrong_create_card'));
                }
               
                if($getPlan->slug == 'life_time'){
                    /* Billing address */
                    $subscriptionCheckout = StripeService::createLifeTimeSubscription([
                        'price' => $getPlan->price,
                        'customer_id' => $getUser->customer_id,
                        'card_id' => $cardSatatus['card_id'],
                        'name' => $getUser->first_name.''.$getUser->last_name,
                        'address' => $getUser->country,
                        'country' => $getUser->country,
                        'city' => $getUser->city,
                        'state' => $getUser->state,
                        'postal_code' => $getUser->zip_code,
                        'country_short_name' => $getUser->country_short_name
                    ]);
    
                    if(empty($subscriptionCheckout)){
                        throw new Exception(__('message.something_went_wrong_while_create_subscription'));
                    }
                    
                    /* create subscription into forevory */  
                    $getSubscription =  self::createLifeTimeSubscription([
                        'user_id'=> $getUser->id,
                        'plan_id'=> $getPlan->id,
                        'plan_price'=>  $getPlan->price,
                        'card_id'=> $cardSatatus['id'] 
                    ],
                    [
                        'id' => $subscriptionCheckout['id']
                    ]);

                }else{
                    /* create subscription into strip */
                    $createSubscription = StripeService::createSubscription([
                        'customer_id' => $cardSatatus['customer_id'],
                        'card_id'=> $cardSatatus['card_id'], 
                        'stripe_plan_id'=> $getPlan->stripe_price_id, 
                        'trial_days'=> $getPlan->trial_days 
                    ]);

                    if(empty($createSubscription)){
                        throw new Exception(__('message.something_went_wrong_while_create_subscription'));
                    }
                    
                    /* Create subscription into forevory */  
                    $getSubscription = self::createSubscription([
                        'user_id'=> $getUser->id,
                        'plan_id'=> $getPlan->id,
                        'plan_price'=> $getPlan->price,
                        'card_id'=> $cardSatatus['id']
                    ],
                    [
                        'id'=> $createSubscription['id'],
                        'trial_start'=> $createSubscription['trial_start'],
                        'trial_end'=> $createSubscription['trial_end'],
                        'current_period_start'=> $createSubscription['current_period_start'],
                        'current_period_end'=> $createSubscription['current_period_end'],
                    ]);

                }
              
                if(empty($getSubscription)){
                    throw new Exception(__('message.something_went_wrong_while_create_subscription'));
                }
                    
                return $getSubscription;

            }else{
                /* Create customer using login user email */
                $createCustomer = StripeService::createCustomer(['email'=> $getUser->email]);
              
                if(empty($createCustomer)){
                    throw new Exception(__('message.something_went_wrong_while_creating_customer'));
                }
                
                /* Get card token */
                $createCardToken = !empty($request['card_token']) ? $request['card_token'] : StripeService::getToken($request);
                
                if(empty($createCardToken)){
                    throw new Exception(__('message.something_went_wrong_create_card'));
                }

                /* save card into stripe */
                $saveCardRequest['customer_id'] = $createCustomer;
                $saveCardRequest['token'] = $createCardToken;
                $createCard = StripeService::saveCard($saveCardRequest);
             
                if(empty($createCard)){
                    throw new Exception(__('message.something_went_wrong_while_save_the_card'));
                }

                /* save card into forevory */
                $createCard['email'] =   $request['email'];
                $getSaveCard = UserCardRepository::createCard($createCard);

                if($getPlan->slug == 'life_time'){
                    /* Billing address */

                    $subscriptionCheckout = StripeService::createLifeTimeSubscription([ 
                        'price' => $getPlan->price,
                        'customer_id' => $createCustomer,
                        'card_id' => $getSaveCard['card_id'],
                        'name' => $getUser->first_name.''.$getUser->last_name,
                        'address' => $getUser->country,
                        'country' => $getUser->country,
                        'city' => $getUser->city,
                        'state' => $getUser->state,
                        'postal_code' => $getUser->zip_code,
                        'country_short_name' => $getUser->country_short_name
                    ]);
                   
                    if(empty($subscriptionCheckout)){
                        throw new Exception(__('message.something_went_wrong_while_create_subscription'));
                    }
                    /* create subscription into forevory */  
                    $getSubscription =  self::createLifeTimeSubscription([
                        'user_id'=> $getUser->id,
                        'plan_id'=> $getPlan->id,
                        'plan_price'=>  $getPlan->price,
                        'card_id'=> $getSaveCard['id'] 
                    ],
                    [
                        'id' => $subscriptionCheckout['id']
                    ]);
                   
                }else{
                    /* Create subscription into strip */
                    $createSubscription = StripeService::createSubscription([
                        'customer_id'=> $createCustomer,
                        'card_id'=> $createCard['id'],
                        'stripe_plan_id'=> $getPlan->stripe_price_id, 
                        'trial_days'=> $getPlan->trial_days
                    ]);
                    
                    if(empty($createSubscription)){
                        throw new Exception(__('message.something_went_wrong_while_create_subscription'));
                    }

                     /* Create subscription into forevory */  
                     $getSubscription =  self::createSubscription([
                        'user_id'=> $getUser->id,
                        'plan_id'=> $getPlan->id,
                        'plan_price'=> $getPlan->price,
                        'card_id'=>  $getSaveCard->id
                    ],
                    [
                        'id'=> $createSubscription['id'],
                        'trial_start'=> $createSubscription['trial_start'],
                        'trial_end'=> $createSubscription['trial_end'],
                        'current_period_start'=> $createSubscription['current_period_start'],
                        'current_period_end'=> $createSubscription['current_period_end'],
                    ]);
                    
                    if(empty($getSubscription)){
                        throw new Exception(__('message.something_went_wrong_while_create_subscription'));
                    }
                }  
                
                return $getSubscription;
                
            } 

        }catch(\Exception $ex){
            throw $ex;
        }
    }

   /**
    * Create lifetime subscription
    * @param Request $request 
    */
    public static function createLifeTimeSubscription($userDetail, $subscriptionDetail){ 
        try{

            /* Update tree */
            $tree = [
                "li0" => [
                    "a0" => [
                        "name" => 'Ralph “Ralphy” Sarris',
                        "gender" => 'male',
                        "dobDate" => '08/10/1950',
                        "dodDate" => '01/01/1970',
                        "relation" => "self",
                        "pic" =>  url('assets/images/view-profile/ralph.png')
                    ]
                ]
            ];
            $data = json_encode($tree);
            $profile = Profile::Create(['user_id' => $userDetail['user_id'], 'prodigy_status'=> '0', 'family_tree'=> $data]);
     
            if(empty($profile)){
                throw new Exception(__('message.something_went_wrong_while_creating_profile'));
            }
      
            /* Create profile subscriprion */
            $createSubscription = ProfileSubscription::create([
                'profile_id' => $profile->id,
                'card_id' => $userDetail['card_id'],
                'subscription_id' => $subscriptionDetail['id'],
                'purchase_plan_id' => $userDetail['plan_id'],
                'plan_id' => $userDetail['plan_id'],
                'subscription_price' => $userDetail['plan_price'],
                'start_date' => date('Y-m-d H:i:s'),
            ]);

            if(empty($createSubscription)){
                throw new Exception(__('message.something_went_wrong_while_create_subscription'));
            }
            /* Generate qr code */ 
            $getProfileQrCode = ProfileRepository::generateProfileQrCode($createSubscription);
            if(!empty($getProfileQrCode)){
                /* Share qr code to prodigy */
                $prodigyStatus = ProdigyService::sendToprodigy($profile->id);
                /* Update prodigy status in profile */
                if(!empty($prodigyStatus)){
                    
                    $profile->prodigy_status = '1';
                    $profile->save();

                }
            }

            return  array('profile_id' => $profile->id, 'qrcode_image' =>!empty($getProfileQrCode) ? $getProfileQrCode->qrcode_image : '', 'shared_link'=>!empty($getProfileQrCode) ? $getProfileQrCode->shared_link : '');

        }catch(\Exception $ex){
            throw $ex;
        }
    }

   /**
    * Create subscription
    * @param Request $request 
    */
    public static function createSubscription($userDetail, $subscriptionDetails){ 
        try{
            
            /* Update tree */
            $tree = [
                "li0" => [
                    "a0" => [
                        "name" => 'Ralph “Ralphy” Sarris',
                        "gender" => 'male',
                        "dobDate" => '08/10/1950',
                        "dodDate" => '01/01/1970',
                        "relation" => "self",
                        "pic" =>  url('assets/images/view-profile/ralph.png')
                    ]
                ]
            ];
            $data = json_encode($tree);
            /* Create user profile */
            $profile = Profile::Create(['user_id' => $userDetail['user_id'], 'prodigy_status'=> '0', 'family_tree'=> $data]);
            
            if(empty($profile)){
                throw new Exception(__('message.something_went_wrong_while_creating_profile'));
            }
            $profile->profile_id = $profile->id;
            /* Generate qr code */ 
            $getQrCode = ProfileRepository::generateProfileQrCode($profile);

            $free_trial = SubscriptionPlanRepository::findOne(['slug'=>'free_trial']);

            $createSubscription = ProfileSubscription::create([
                'profile_id' => $profile->id,
                'card_id' => $userDetail['card_id'],
                'purchase_plan_id' => $userDetail['plan_id'],
                'subscription_price' => $userDetail['plan_price'],
                'subscription_id' => $subscriptionDetails['id'],
                'plan_id' => $free_trial->id,
                'free_trial_days' => $free_trial->days,
                'free_trial_start' => date('Y-m-d H:i:s',$subscriptionDetails['trial_start']),
                'free_trial_end' => date('Y-m-d H:i:s',$subscriptionDetails['trial_end']),
                'start_date' => date('Y-m-d H:i:s',$subscriptionDetails['current_period_start']),
                'end_date' => date('Y-m-d H:i:s',$subscriptionDetails['current_period_end']),
            ]);

            if(empty($createSubscription)){
                throw new Exception(__('message.something_went_wrong_while_create_subscription'));
            }
            

            return  array('profile_id' => $profile->id, 'qrcode_image' =>!empty($getQrCode) ? $getQrCode->qrcode_image : '', 'shared_link'=>!empty($getQrCode) ? $getQrCode->shared_link : '');

        }catch(\Exception $ex){
            throw $ex;
        }
    }

   /**
    * Create customer card or delete if exist.
    * @param $userCardDetail, $postRequest
    */
    public static function createCustomerCard($userCardDetail, $postRequest){ 
        try{
            
            $stripeCreatedCard = StripeService::saveCard($userCardDetail);
            
            if(empty($stripeCreatedCard)){
                throw new Exception(__('message.something_went_wrong_create_card'));
            }
            
            /* Check if card is already exist */
            $getUserExistCard = UserCardRepository::findOne(['user_id' =>$userCardDetail['user_id'] ,'card_key' => $stripeCreatedCard->fingerprint]);
          
            if(!empty($getUserExistCard)){
                
                $removeCard = StripeService::removeCard(['customer_id'=> $stripeCreatedCard->customer,'card_id'=> $stripeCreatedCard->id]);
                
                if(empty($removeCard)){
                    throw new Exception(__('message.something_went_wrong_while_checking_card_details'));
                }

                throw new Exception(__('message.card_detail_is_already_added'));
 
            }else{
                $stripeCreatedCard['email'] = $postRequest['email'];
                $createSaveCard = UserCardRepository::createCard($stripeCreatedCard);

                if(empty($createSaveCard)){
                    throw new Exception(__('message.something_went_wrong_create_card'));
                }

                return array('customer_id' => $stripeCreatedCard->customer, 'card_id'=> $stripeCreatedCard->id, 'id' => $createSaveCard->id);
            }  

        }catch(\Exception $ex){
            throw $ex;
        }
    }

   /**
    * Profile subscription service start
    * @param $request
    * @return boolean
    */
    public static function profileSubscriptionServiceStart($request){
        DB::beginTransaction();
        try{
            if(!empty($request) && !empty($request->status)){
                $getSubscription = self::findOne(['subscription_id'=>$request->id]);
                /* Renew subscription or end trial */
                if(!empty($getSubscription) && $request->status == 'active'){
        
                    $getSubscription->start_date = date('Y-m-d H:i:s',$request->current_period_start);
                    $getSubscription->end_date = date('Y-m-d H:i:s',$request->current_period_end);
                    $getSubscription->plan_id = $getSubscription->purchase_plan_id;
                    $getSubscription->status = 'active';
                    $getSubscription->save();
                   
                    if(!empty($getSubscription)){
                    
                        $getProfile = ProfileRepository::findOne(['id'=>$getSubscription->profile_id]);
                        $getProfile->status = 'active';
                        if($getProfile->prodigy_status =='0'){
                            /* Send QR to prodigy */
                            $prodigyStatus = ProdigyService::sendToprodigy($getSubscription->profile_id);
                            if(!empty($prodigyStatus)){
                                /* Update prodigy status in respective profile */
                                $getProfile->prodigy_status = '1'; 
                            }

                        }
                        $getProfile->save();
                        
                        if(!empty($getProfile)){
                            DB::commit();
                            return true;
                        }

                        DB::rollback();
                        Log::debug('profile.status.notUpdated', ['error' => 'Profile status not updated', 'subscription_id' => $request->id]);
                        return false;

                    }

                    DB::rollback();
                    Log::debug('webhook.subscription.status.notUpdated', ['error' => 'Profile subscription not updated', 'subscription_id' => $request->id]);
                    return false;

                }elseif(!empty($getSubscription) && $request->status == 'past_due'){
                    $getProfile = ProfileRepository::findOne(['id'=>$getSubscription->profile_id], ['user']);
                    /* If payment decliend after one hours then expired the current plan */
                    $cancelDeclinedSubscription = StripeService::cancelSubscription(['subscription_id'=> $request->id]);

                    if(!empty($cancelDeclinedSubscription) && $cancelDeclinedSubscription->status == 'canceled'){
                        $adminUser = getAdmin();
                        /* Save notification  */
                        $notifaction =
                            [
                                'user_id' => $adminUser->id,
                                'profile_id' => $getProfile->id,
                                'title' => "Payment Alert",
                                'message' => "". ucwords($getProfile->user->first_name.' '.$getProfile->user->last_name)."'s payment on their ". ucwords($getProfile->profile_name) ." profile has been declined for subscription renewal.",
                                'type' => 'declined'
                            ];

                        $result = Notification::create($notifaction);
                        DB::commit();
                        return true;
                    }else{
                        DB::rollback();
                        Log::debug('decliend.subscription.notUpdated', ['error' => 'Profile subscription not updated', 'subscription_id' => $request->id]);
                        return false;
                    }

                }
                else
                {
                    /* If payment declined */
                    if(!empty($getSubscription) && $request->status != 'active' && $request->status != 'canceled' && $request->status != 'trialing'){
                        ProfileSubscription::where(['subscription_id' => $request->id])->update(['start_date'=>  date('Y-m-d H:i:s',$request->current_period_start), 'end_date'=> date('Y-m-d H:i:s',$request->current_period_end),  'status' => 'expired', 'stripe_status'=> 'canceled']);

                        ProfileRepository::updateProfileStatus(['id'=>$getSubscription->profile_id, 'status'=> 'expired']);

                        DB::commit();
                        return true;
                    }
                }
            }
        }catch(\Exception $ex){
            DB::rollback();
            throw $ex; 
        }
    }

   /**
    * Profile subscription cancel form webhook.
    * @param $request
    *
    */
    public static function profileSubscriptionCancel($request){
        DB::beginTransaction();
        try{

            if($request->status == 'canceled'){

                $getSubscriptionProfile = self::findOne(['subscription_id'=> $request->id ]);
                
                /* Check if no plan is active on the same profile then expired the current plan and profile */
                if(date('Y-m-d H:i:s',$request->current_period_end) >= $getSubscriptionProfile->end_date){
                    
                    /* Check if new plan is active on the same profile  */
                    $getActiveSubscriptionProfile = ProfileSubscription::where(['profile_id' => $getSubscriptionProfile->profile_id, 'status' => 'active' ,'stripe_status' => 'active'])->whereNotIn('subscription_id', [$request->id])->first();
                   
                    if(!empty($getSubscriptionProfile)){

                        if(($getSubscriptionProfile->status == 'active' && $getSubscriptionProfile->stripe_status == 'active' && empty($getActiveSubscriptionProfile)) || $getSubscriptionProfile->status == 'active' && $getSubscriptionProfile->stripe_status == 'canceled' && empty($getActiveSubscriptionProfile)){
                           
                            /* If updated service is in active mode */
                            $subscriptionCancelResult =  ProfileSubscription::where(['subscription_id' => $request->id])->update(['start_date'=>  date('Y-m-d H:i:s',$request->current_period_start), 'end_date'=> date('Y-m-d H:i:s',$request->current_period_end),  'status' => 'expired', 'stripe_status'=> 'canceled']);

                            $profileUpdateResult =  ProfileRepository::updateProfileStatus(['id'=>$getSubscriptionProfile->profile_id, 'status'=> 'expired']);

                        }else{

                            /* If updated service is in active mode */
                            $subscriptionCancelResult =  ProfileSubscription::where(['subscription_id' => $request->id])->update(['start_date'=>  date('Y-m-d H:i:s',$request->current_period_start), 'end_date'=> date('Y-m-d H:i:s',$request->current_period_end),  'status' => 'canceled', 'stripe_status'=> 'canceled']);

                        }

                        if(empty($subscriptionCancelResult)){
                            DB::rollback();
                            $errorMessage = __('message.something_went_wrong_while_updating_subscription_detail');
                            Log::debug('webhook.subscription.canceled.error', ['error'=> $errorMessage, 'request'=> $request]);
                            return false;
                        }
                    }

                    DB::commit();
                    return true;
                }
           }

        }catch(\Exception $ex){
            DB::rollback();
            throw $ex; 
        }
    }

   /**
    * Get user active subscription.
    * @param $cardId
    *
    */
    public static function getSubscriptionByCardId($request){
        try{
   
            $getActiveSubscription = ProfileSubscription::where(['card_id' => $request['card_id']])->where(function ($query) {
                $query->Where('status', '=', 'active')
                      ->orWhere('status', '=', 'expired');
            })->orderBy('id', 'desc')->get();

            if(!empty($getActiveSubscription)){

                return $getActiveSubscription;
            } 

            return false;
        }catch(\Exception $ex){
             throw $ex; 
        }
    }

   /**
    * Update subscription card id.
    * @param $subscriptionId $cardId
    */
    public static function updateSubscriptionCard($subscriptionId, $cardId){
        try{

            $getActiveSubscription = ProfileSubscription::where(['id' => $subscriptionId])->update(['card_id' => $cardId]);
            if(!empty($getActiveSubscription)){
                
                return true;
            }

            throw new Exception(__('message.something_went_wrong_while_updating_card_detail'));
        }catch(\Exception $ex){
             throw $ex; 
        }
    }

   /**
    * Get active profile count
    * @return profileCount
    */
    public static function activeProfileCount($request='', $startDate='', $endDate='')
    {   
        if(empty($startDate) && empty($endDate)){
            $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');
        }
        $activeProfileCount= ProfileSubscription::where('status','active');
            if(!empty($request['subscriptionPlan']) && $request['subscriptionPlan'] != 'all'){
                $activeProfileCount = $activeProfileCount->where('purchase_plan_id', $request['subscriptionPlan']);
            }
            return $activeProfileCount = $activeProfileCount->whereBetween(DB::raw('DATE(created_at)'),[$startDate,$endDate])->count();
    }

   /**
    * Get Unsubscribed count
    * @return profileCount
    */
    public static function getUnsubscribedCount($request='', $startDate='', $endDate='')
    {   
        if(empty($startDate) && empty($endDate)){
            $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');
        }
        
        $profile = ProfileSubscription::where('status','!=','active');
        if(!empty($request['subscriptionPlan']) && $request['subscriptionPlan'] != 'all'){
            $profile = $profile->where('purchase_plan_id', $request['subscriptionPlan']);
        }
        $profile = $profile->whereBetween(DB::raw('DATE(created_at)'),[$startDate,$endDate])->groupBy('profile_id')->get();
        if(!empty($profile)) {
            return count($profile);
        } else {
            return 0;
        }
    }

   /**
    * Get Graph Activity Data
    * @return profileCount
    */
    public static function getGraphActivityData($request)
    {   
        $range = $request->limit;
        $data = [];
        /* Start for every month */
        if($range == 'year') {
            /* Start get data of every month */
            $now = Carbon::now();
            $currentYear = $now->year;
            $currentMonth = $now->month;
            
            for($i=1;$i<=12;$i++) {

                if($i <= 9) {
                    $month = '0'.$i;
                } else {
                    $month = $i;
                }

                $start_date = $currentYear.'-'.$month.'-01';
                $end_date = $currentYear.'-'.$month.'-31';
                $monthName = Carbon::parse($start_date)->format('F');

                if($month <= $currentMonth) {
                    /* Get subscriptions data */
                    $subscriptions = ProfileSubscription::whereBetween('created_at',[$start_date,$end_date]);
                    if(!empty($request['subscriptionPlan']) && $request['subscriptionPlan'] != 'all'){
                        $subscriptions = $subscriptions->where('purchase_plan_id', $request['subscriptionPlan']);
                    }
                    $subscriptions = $subscriptions->count();
                } else {
                    $subscriptions = 0;
                }
                $data['labels'][] = $monthName;
                $data['subscriptions'][] = $subscriptions;

            }
        }
        /* Start for last 30 Day */
        elseif($range == 'month') {
            /* Start get data of every day */
            $now = Carbon::now();
            $currentYear = $now->year;
            $currentMonth = $now->month;
            $currentDay = $now->day;
            $days = $now->daysInMonth;
             
            for($i=1;$i<=$days;$i++) {
                if($i <= 9) {
                    $day = '0'.$i;
                } else {
                    $day = $i;
                }

                if($day <= $currentDay) {
                    /* Get subscriptions data */
                    $date = $currentYear.'-'.$currentMonth.'-'.$day;
                    $subscriptions = ProfileSubscription::whereDate('created_at',$date);
                    if(!empty($request['subscriptionPlan']) && $request['subscriptionPlan'] != 'all'){
                        $subscriptions = $subscriptions->where('purchase_plan_id', $request['subscriptionPlan']);
                    }
                    $subscriptions = $subscriptions->count();
                } else {
                    $subscriptions = 0;
                }

                $data['labels'][] = (int) $day;
                $data['subscriptions'][] = $subscriptions;
            }
        }
        /* Start for last 7 Day */
        elseif($range == 'week') {
            /* Start get data of every day */
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->format('Y-m-d');
            $date = $startDate;
            $dayName = $now->startOfWeek()->format('l');

            $endDate = $now->endOfWeek()->format('Y-m-d');
            $currentDate = $now->format('Y-m-d');

            for($i=1;$i<=7;$i++) {
                if($date <= $currentDate) {
                    $subscriptions = ProfileSubscription::whereDate('created_at',$date);
                    if(!empty($request['subscriptionPlan']) && $request['subscriptionPlan'] != 'all'){
                        $subscriptions = $subscriptions->where('purchase_plan_id', $request['subscriptionPlan']);
                    }
                    $subscriptions = $subscriptions->count();
                } else {
                    $subscriptions = 0;
                }

                $data['labels'][] = $dayName;
                $data['subscriptions'][] = $subscriptions;

                $nextDate = Carbon::parse($date)->addDay();
                $date = $nextDate->format('Y-m-d');
                $dayName = $nextDate->format('l');
            }
        }
        /* Start for get every day of a date range */
        else {
            $startDate = getDBFormatDate($request->startDate);
            $endDate = getDBFormatDate($request->endDate);

            if($endDate < $startDate){
                throw new Exception(__('message.start_end_date_validation'));
            }
            
            /* Get subscriptions data */
            $result = ProfileSubscription::whereBetween(DB::raw('DATE(created_at)'),[$startDate,$endDate]);
                if(!empty($request['subscriptionPlan']) && $request['subscriptionPlan'] != 'all'){
                    $result = $result->where('purchase_plan_id', $request['subscriptionPlan']);
                }
                $result = $result->select(DB::raw('count(id) as `subscription`'), DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') subscription_date"))
                ->groupby('subscription_date')
                ->orderby('created_at','asc')
                ->get();

            if(!empty($result)) {
                foreach($result as $row) {
                    $data['labels'][] = $row->subscription_date;
                    $data['subscriptions'][] = array('x'=>$row->subscription_date,'y'=>$row->subscription);
                }
            }

        }
        return $data;
    }

   /**
    * Update subscription stripe status.
    * @param $where, $data
    */
    public static function updateProfileSubscription($where, $data){
        try{
            $getUpdateSubscription = ProfileSubscription::where($where)->update($data);
            
            if(!empty($getUpdateSubscription)){
                return true;
            }

            throw new Exception(__('message.something_went_wrong_while_updating_stripe_status'));
        }catch(\Exception $ex){
             throw $ex; 
        }
    }

   /**
    * Get profile transection and subscription list.
    * @param $request
    * @return array
    */
    public static function getProfileTransactions($request, $where="" , $listType){
        try{
            $post = $request->all();
            $userId = getUserDetail()->id;
            $paginationLimit = Config::get('constants.DefaultValues.PAGINATION_RECORD');
            /* Check page limit */
            if (!empty($post['page_limit']) && $post['page_limit'] > 0) {
                $paginationLimit = $post['page_limit'];
            }
            /* Get transection current and upcoming plan */
            $list = ProfileSubscription::select('profile_subscriptions.id','profile_subscriptions.profile_id','profiles.profile_name', 'subscription.id as purchase_plan_id', 'subscription.plan as purchase_plan','subscription.slug', DB::raw("CONCAT('$', profile_subscriptions.subscription_price) AS purchase_plan_price"), 'current_plan.id as current_plan_id', 'current_plan.plan as current_plan', DB::raw("CONCAT('$', profile_subscriptions.subscription_price) AS current_plan_price"),  'profile_subscriptions.created_at', 'profile_subscriptions.purchase_plan_id', 'profile_subscriptions.start_date', 'profile_subscriptions.end_date', 'profile_subscriptions.status', 'profile_subscriptions.stripe_status','profiles.purchase_type')
               
            ->join('subscription_plans AS subscription', 'subscription.id', '=', 'profile_subscriptions.purchase_plan_id')
            ->join('subscription_plans AS current_plan', 'current_plan.id', '=', 'profile_subscriptions.plan_id')
    

            ->join('profiles', function ($join) use ($userId) {
                $join->on('profiles.id', '=', 'profile_subscriptions.profile_id')->where('profiles.user_id', '=', $userId);
            });
            /* Check if not empty where */
            if(!empty($where)){
                $list->where('profile_subscriptions.status', '!=' , $where);
            }
            /* Search by name and current plan */
            if (!empty($post['search']) && $listType == 'subscription') {

                $search = $post['search'];
                $list->whereRaw("(profiles.profile_name like '%$search%' OR
                current_plan.plan like '%$search%')");
                
            } 
            /* Search by name and purchase plan */
            if (!empty($post['search']) && $listType == 'transection') {
                
                $search = $post['search'];
                $list->whereRaw("(profiles.profile_name like '%$search%' OR
                subscription.plan like '%$search%')");

            }

            $list->orderBy('profile_subscriptions.id','desc');

            /* Check page and render result */ 
            return $list->simplePaginate($paginationLimit);

        }catch(\Exception $ex){
             throw $ex; 
        }
    }

    /**
    * Apple subscriptoins
    * @param Request $request 
    */
    public static function saveTransactionDetail($request){ 
        try{
            if(!empty($request->receipt)) {
                $receiptData = receipt_Result_ITC($request->receipt);
                if($receiptData) {
                    $subscriptionData = $receiptData->latest_receipt_info[0];
                    if($request->subscriptionType == 'onCreateProfile') {
                        /* To create profile with new subscription */
                        return self::createAppleProfile($request,$subscriptionData);
                    } elseif($request->subscriptionType == 'onSwitchPlan') {
                        /* To switch subscription with exiting profile */
                        return self::switchAppleProfilePlan($request,$subscriptionData);
                    } elseif($request->subscriptionType == 'onBuyPlan') {
                        /* To Buy subscription with exiting profile after cancel or expire */
                        return self::buyAppleProfilePlan($request,$subscriptionData);
                    }
                } else {
                    Log::debug('iOS-Invalid-Receipt',['data'=>$request->all()]);
                    throw new Exception(__('Invalid Receipt'));
                }

            }
            
            throw new Exception(__('Receipt not found'));
        }catch(\Exception $ex){
            throw $ex;
        }
    }

    /**
    * Create subscription of Apple
    * @param Request $request 
    */
    public static function createAppleProfile($request,$subscriptionData){ 
        DB::beginTransaction();
        try{

            $loginUser = getUserDetail();
            /* Update tree */
            $tree = [
                "li0" => [
                    "a0" => [
                        "name" => 'Ralph “Ralphy” Sarris',
                        "gender" => 'male',
                        "dobDate" => '08/10/1950',
                        "dodDate" => '01/01/1970',
                        "relation" => "self",
                        "pic" =>  url('assets/images/view-profile/ralph.png')
                    ]
                ]
            ];
            $data = json_encode($tree);
            /* Create user profile */
            $profile = Profile::Create(['user_id' => $loginUser->id, 'prodigy_status'=> '0', 'family_tree'=> $data,'purchase_type'=>'ios']);
            
            if(empty($profile)){
                DB::rollBack();
                throw new Exception(__('message.something_went_wrong_while_creating_profile'));
            }
            $profile->profile_id = $profile->id;
            /* Generate qr code */ 
            $getQrCode = ProfileRepository::generateProfileQrCode($profile);

            $purchasePlan = SubscriptionPlanRepository::findOne(['apple_product_id'=>$subscriptionData->product_id]);
            if($purchasePlan->slug == 'life_time') {
                $createSubscription = ProfileSubscription::create([
                    'profile_id' => $profile->id,
                    'purchase_plan_id' => $purchasePlan->id,
                    'subscription_price' => $purchasePlan->apple_price,
                    'subscription_id' => $subscriptionData->original_transaction_id,
                    'plan_id' => $purchasePlan->id,
                    'start_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->purchase_date)),
                    'subscriptions_response' => json_encode($request->all())
                ]);
            } else {

                $free_trial = SubscriptionPlanRepository::findOne(['slug'=>'free_trial']);
                $createSubscription = ProfileSubscription::create([
                    'profile_id' => $profile->id,
                    'purchase_plan_id' => $purchasePlan->id,
                    'subscription_price' => $purchasePlan->apple_price,
                    'subscription_id' => $subscriptionData->original_transaction_id,
                    'plan_id' => $free_trial->id,
                    'free_trial_days' => $free_trial->days,
                    'free_trial_start' => date('Y-m-d H:i:s',strtotime($subscriptionData->purchase_date)),
                    'free_trial_end' => date('Y-m-d H:i:s',strtotime($subscriptionData->expires_date)),
                    'start_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->purchase_date)),
                    'end_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->expires_date)),
                    'subscriptions_response' => json_encode($request->all())
                ]);
            }
            if(empty($createSubscription)){
                DB::rollBack();
                throw new Exception(__('message.something_went_wrong_while_create_subscription'));
            }
            
            DB::commit();
            return  array('profile_id' => $profile->id, 'qrcode_image' =>!empty($getQrCode) ? $getQrCode->qrcode_image : '', 'shared_link'=>!empty($getQrCode) ? $getQrCode->shared_link : '');

        }catch(\Exception $ex){
            DB::rollBack();
            throw $ex;
        }
    }

    /**
    * Switch subscription of Apple
    * @param Request $request 
    */
    public static function switchAppleProfilePlan($request,$subscriptionData){ 
        DB::beginTransaction();
        try {
            $adminUser = getAdmin();

            /* Check profile*/
            $getProfile = ProfileRepository::findOne(['id'=>$request->profileId,'purchase_type'=>'ios','status'=>'active']);
            if(empty($getProfile)) {
                throw new Exception(__('message.profile_not_found'));
            }

            /* Check existing plan*/
            $currentPlan = ProfileSubscription::where(['subscription_id'=>$subscriptionData->original_transaction_id,'status'=>'active'])->first();
            if(empty($currentPlan)) {
                throw new Exception(__('message.no_active_plan_found_to_switch'));
            }

            /* Check switched plan*/
            $switchPlan = SubscriptionPlanRepository::findOne(['apple_product_id'=>$subscriptionData->product_id]);
            if(empty($switchPlan)) {
                throw new Exception(__('message.plan_does_not_exist'));
            }
            

            /* Cancelled current subscription */
            ProfileSubscription::where('id', $currentPlan->id)->update([
                'status'=> 'canceled', 
                'stripe_status'=>'canceled'
            ]);

            /* Check for switched subscription plan */
            if($switchPlan->slug == 'life_time')
            {
                /* Switch to new plan */
                $getSwitchSubscription =  ProfileSubscription::create([
                    'profile_id' => $currentPlan->profile_id,
                    'purchase_plan_id'=> $switchPlan->id,
                    'subscription_price'=> $switchPlan->apple_price,
                    'subscription_id' => $subscriptionData->original_transaction_id,
                    'plan_id'=> $switchPlan->id, 
                    'start_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->purchase_date)),
                    'subscriptions_response' => json_encode($request->all())
                ]);
        
                if(empty($getSwitchSubscription)) {
                    DB::rollback();
                    return false;
                }
                
            }
            else
            {
                /* Switch to new plan */
                $getSwitchSubscription = ProfileSubscription::create([
                    'profile_id' => $currentPlan->profile_id,
                    'subscription_id' => $subscriptionData->original_transaction_id,
                    'plan_id' => $switchPlan->id,
                    'purchase_plan_id' => $switchPlan->id,
                    'subscription_price' => $switchPlan->apple_price,
                    'start_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->purchase_date)),
                    'end_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->expires_date)),
                    'subscriptions_response' => json_encode($request->all())
                ]);
                
                if(empty($getSwitchSubscription)){
                    DB::rollback();
                    return false;
                }
            }

            /* Save notification  */
            $notifaction =
            [
                'user_id' => $adminUser->id,
                'profile_id' => $currentPlan->profile_id,
                'title' => "Account Alert",
                'message' => "".ucwords($currentPlan->profile->user->first_name.' '.$currentPlan->profile->user->last_name) ." has upgraded their subscription to ".strtolower($switchPlan->plan).".",
                'type' => 'upgrade'
            ];

            Notification::create($notifaction);

            DB::commit();
            return __('message.plan_switch_success');
            
        } catch(\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    /**
    * Buy new subscription of Apple
    * @param Request $request 
    */
    public static function buyAppleProfilePlan($request,$subscriptionData){ 
        DB::beginTransaction();
        try {
            
            /* Check profile*/
            $getProfile = ProfileRepository::findOne(['id'=>$request->profileId,'purchase_type'=>'ios']);
            if(empty($getProfile)) {
                throw new Exception(__('message.profile_not_found'));
            }

            /* Check existing plan*/
            $currentPlan = ProfileSubscription::where(['subscription_id'=>$subscriptionData->original_transaction_id])->first();
            if(empty($currentPlan)) {
                throw new Exception(__('message.plan_does_not_exist'));
            }

            /* Check switched plan*/
            $switchPlan = SubscriptionPlanRepository::findOne(['apple_product_id'=>$subscriptionData->product_id]);
            if(empty($switchPlan)) {
                throw new Exception(__('message.plan_does_not_exist'));
            }
            

            /* Cancelled current subscription */
            ProfileSubscription::where('id', $currentPlan->id)->update([
                'status'=> 'canceled', 
                'stripe_status'=>'canceled'
            ]);

            /* Check for switched subscription plan */
            if($switchPlan->slug == 'life_time')
            {                
                /* Switch to new plan */
                $getSwitchSubscription =  ProfileSubscription::create([
                    'profile_id' => $currentPlan->profile_id,
                    'purchase_plan_id'=> $switchPlan->id,
                    'subscription_price'=> $switchPlan->apple_price,
                    'subscription_id' => $subscriptionData->original_transaction_id,
                    'plan_id'=> $switchPlan->id, 
                    'start_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->purchase_date)),
                    'subscriptions_response' => json_encode($request->all())
                ]);
        
                if(empty($getSwitchSubscription)) {
                    DB::rollback();
                    return false;
                }
                
            }
            else
            {
                /* Switch to new plan */
                $getSwitchSubscription = ProfileSubscription::create([
                    'profile_id' => $currentPlan->profile_id,
                    'subscription_id' => $subscriptionData->original_transaction_id,
                    'plan_id' => $switchPlan->id,
                    'purchase_plan_id' => $switchPlan->id,
                    'subscription_price' => $switchPlan->apple_price,
                    'start_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->purchase_date)),
                    'end_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->expires_date)),
                    'subscriptions_response' => json_encode($request->all())
                ]);
                
                if(empty($getSwitchSubscription)){
                    DB::rollback();
                    return false;
                }
            }

            /*  Update profile status */
            $getProfile->status = 'active';
            $getProfile->save();

            DB::commit();
            return $getProfile;
            
        } catch(\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    /**
    * Profile subscription renew for Apple
    * @param $request
    * @return boolean
    */
    public static function profileSubscriptionRenewIos($subscriptionData){
        DB::beginTransaction();
        try{
            if(!empty($subscriptionData)){
                $getSubscription = ProfileSubscription::where(
                    [
                        'subscription_id'=>$subscriptionData->get('originalTransactionId'),
                        'purchase_plan_id'=>$subscriptionData->get('productId'),
                    ])
                    ->whereIn('status',['active','expired'])->orderBy('id','desc')->first();
                
                /* Renew subscription or end trial */
                if(!empty($getSubscription)){
        
                    /* update subscription status */
                    ProfileSubscription::where(['subscription_id' => $getSubscription->id])
                        ->update(
                            [
                                'start_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->get('purchaseDate'))),
                                'end_date' => date('Y-m-d H:i:s',strtotime($subscriptionData->get('expiresDate'))),
                                'plan_id' => $subscriptionData->get('productId'),
                                'status' => 'active',
                                'stripe_status' => 'active'
                            ]
                        );
                   
                    if(!empty($getSubscription)){
                    
                        $getProfile = ProfileRepository::findOne(['id'=>$getSubscription->profile_id]);
                        $getProfile->status = 'active';
                        if($getProfile->prodigy_status =='0'){
                            /* Send QR to prodigy */
                            $prodigyStatus = ProdigyService::sendToprodigy($getSubscription->profile_id);
                            if(!empty($prodigyStatus)){
                                /* Update prodigy status in respective profile */
                                $getProfile->prodigy_status = '1'; 
                            }

                        }
                        $getProfile->save();
                        
                        if(!empty($getProfile)){
                            DB::commit();
                            return true;
                        }

                        DB::rollback();
                        Log::debug('profile.status.notUpdated', ['error' => 'Profile status not updated', 'subscription_id' => $subscriptionData->get('originalTransactionId')]);
                        return false;

                    }

                    DB::rollback();
                    Log::debug('webhook.subscription.status.notUpdated', ['error' => 'Profile subscription not updated', 'subscription_id' => $subscriptionData->get('originalTransactionId')]);
                    return false;

                }
            }
        }catch(\Exception $ex){
            DB::rollback();
            throw $ex; 
        }
    }

    
    /**
    * Profile subscription renew fail for Apple
    * @param $request
    * @return boolean
    */
    public static function profileSubscriptionRenewFailIos($subscriptionData){
        DB::beginTransaction();
        try{
            if(!empty($subscriptionData)) {
                $getSubscription = ProfileSubscription::where(
                    [
                        'subscription_id'=>$subscriptionData->get('originalTransactionId'),
                        'purchase_plan_id'=>$subscriptionData->get('productId'),
                    ])
                    ->whereIn('status',['active','expired'])->orderBy('id','desc')->first();
                /* Renew subscription or end trial */
                if(!empty($getSubscription)){
                    
                    /* update subscription status */
                    ProfileSubscription::where(['subscription_id' => $subscriptionData->get('originalTransactionId')])
                        ->update(
                            [
                                'status' => 'expired',
                                'stripe_status' => 'canceled'
                            ]
                        );
                    
                    /* update profile status */
                    ProfileRepository::updateProfileStatus(['id'=>$getSubscription['profile_id'], 'status'=> 'expired']);

                    /* send notification to admin for fail renew */
                    $getProfile = ProfileRepository::findOne(['id'=>$getSubscription['profile_id']], ['user']);
                    $adminUser = getAdmin();
                    /* Save notification  */
                    $notifaction =
                        [
                            'user_id' => $adminUser->id,
                            'profile_id' => $getProfile->id,
                            'title' => "Payment Alert",
                            'message' => "". ucwords($getProfile->user->first_name.' '.$getProfile->user->last_name)."'s payment on their ". ucwords($getProfile->profile_name) ." profile has been declined for subscription renewal.",
                            'type' => 'declined'
                        ];

                    Notification::create($notifaction);
                    DB::commit();
                    return true;

                }
            }
        }catch(\Exception $ex){
            DB::rollback();
            throw $ex; 
        }
    }

    /**
    * Profile subscription cancel for Apple
    * @param $request
    * @return boolean
    */
    public static function profileSubscriptionCancelIos($subscriptionData,$status){
        DB::beginTransaction();
        try{
            if(!empty($subscriptionData)) {
                $getSubscription = ProfileSubscription::where(
                    [
                        'subscription_id'=>$subscriptionData->get('originalTransactionId'),
                        'purchase_plan_id'=>$subscriptionData->get('productId'),
                    ])
                    ->orderBy('id','desc')->first();
                /* Renew subscription or end trial */
                if(!empty($getSubscription)){
                    
                    /* update subscription status */
                    ProfileSubscription::where([
                                'subscription_id'=>$subscriptionData->get('originalTransactionId'),
                                'purchase_plan_id'=>$subscriptionData->get('productId')
                            ])
                            ->update(
                                [
                                    'status' => $status,
                                    'stripe_status' => 'canceled'
                                ]
                            );
                    
                    /* update profile status */
                    if($status == 'expired') {
                        $profileStatus = 'expired';
                    } else {
                        $profileStatus = 'inactive';
                    }
                    ProfileRepository::updateProfileStatus(['id'=>$getSubscription['profile_id'], 'status'=>$profileStatus]);

                    DB::commit();
                    return true;

                }
            }
        }catch(\Exception $ex){
            DB::rollback();
            throw $ex; 
        }
    }

    /**
    * Profile subscription switch for Apple
    * @param $request
    * @return boolean
    */
    public static function profileSubscriptionEnableIos($subscriptionData){
        DB::beginTransaction();
        try{
            if(!empty($subscriptionData)) {
                $getSubscription = ProfileSubscription::where(
                    [
                        'subscription_id'=>$subscriptionData->get('originalTransactionId'),
                        'purchase_plan_id'=>$subscriptionData->get('productId'),
                    ])
                    ->orderBy('id','desc')->first();
                /* Renew subscription or end trial */
                if(!empty($getSubscription)){
                    
                    /* send notification to user for renew enabled */
                    $getProfile = ProfileRepository::findOne(['id'=>$getSubscription['profile_id']], ['user']);
                    
                    /* Save notification  */
                    $notifaction =
                        [
                            'user_id' => $getProfile->user_id,
                            'profile_id' => $getProfile->id,
                            'title' => "Payment Alert",
                            'message' => "You have enabled subscription renew with Legacy Plan. There is no need to active subscription renew with Legacy Plan for profile". ucwords($getProfile->profile_name) ." profile has been declined for subscription renewal.",
                            'type' => 'enabled'
                        ];

                    Notification::create($notifaction);
                    DB::commit();
                    return true;

                }
            }
        }catch(\Exception $ex){
            DB::rollback();
            throw $ex; 
        }
    }

    /**
    * Profile subscription switch for Apple
    * @param $request
    * @return boolean
    */
    public static function profileSubscriptionPurchaseIos($subscriptionData){
        DB::beginTransaction();
        try{
            
        }catch(\Exception $ex){
            DB::rollback();
            throw $ex; 
        }
    }

    /**
    * Profile subscription switch for Apple
    * @param $request
    * @return boolean
    */
    public static function profileSubscriptionSwitchIos($subscriptionData,$switchData){
        DB::beginTransaction();
        try{
            
        }catch(\Exception $ex){
            DB::rollback();
            throw $ex; 
        }
    }

}
