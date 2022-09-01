<?php

namespace App\Repositories;

use App\Models\UserCard;
use App\Repositories\UserRepository;
use App\Repositories\ProfileSubscriptionRepository;
use App\Services\StripeService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserCardRepository{
    
    /**
     * Find one
     * @param array $where
     * @return  UserCard
     */
    public static function findOne($where)
    {
        try{
            return UserCard::where($where)->first();
        }catch(Exception $ex){
            throw $ex;
        }
    }

    /**
     * Function used to create card details.
     * @param $request
     * @return Boolean
     */
    public static function createCard($request)
    {
        try{

            $getUserDetail = getUserDetail();
            /* Check if user have save card  */
            $getUserSavedCard = self::findOne(['user_id' => $getUserDetail->id]);
           
            $createCardData = ['user_id' => $getUserDetail->id,'card_name' => $request['name'],'card_id' => $request['id'], 'card_type' => $request['brand'],'customer_id' => $request['customer'], 'last_digit' => $request['last4'], 'card_key' => $request['fingerprint'], 'email' => $request['email'], 'exp_month' => $request['exp_month'], 'exp_year' => $request['exp_year'],
            ];
            /* If no card is save then make it as default */
            if(empty($getUserSavedCard)){
                $createCardData['is_default'] = 1;
            }
           
            $creatCard = UserCard::create($createCardData);
            if(!empty($creatCard)){
                
                /* If user custome not created */
                if(empty($getUserDetail->customer_id)){

                $customerCreated = UserRepository::update(['id'=>$getUserDetail->id], ['customer_id' => $request['customer']]); 
                if(!empty($customerCreated)){

                    return $creatCard;
                }

                }else{

                    return $creatCard;
                }
            }
            throw new Exception(__('message.something_went_wrong_create_card'));
        }catch(Exception $ex){
            throw $ex;
        }
    }

    /**
     * Function used to get user save card.
     * @return array
     */
    public static function getSaveCard()
    {
        try{
            $userId = getUserDetail()->id;
            return UserCard::select('id', 'user_id', 'card_id', 'card_name', 'card_type', 'last_digit', 'email', 'is_default', 'created_at', 'updated_at', 'status', 'exp_month', 'exp_year', DB::raw("(CASE WHEN card_type = 'Visa' THEN '/assets/images/visa.png' WHEN card_type = 'American Express' THEN '/assets/images/americanexpress.png' WHEN card_type = 'MasterCard' THEN '/assets/images/mastercard.png' END) AS card_url"))->where(['status'=> 'active', 'user_id' => $userId])->orderBy('is_default', 'asc')->get();
        }catch(Exception $ex){
            throw $ex;
        }
    }

    /**
     * Add new card
     * @param Request
     * @return boolean
     */
    public static function addNewCard($request)
    {
        try{
            $getUserDetail = getUserDetail();

            if(!empty($request['exp_date'])){
                $exp_date = $request['exp_date'];
                $explodeExpDate = explode("/", $exp_date);
                $request['exp_month'] = $explodeExpDate[0];
                $request['exp_year'] = $explodeExpDate[1];
            }
         
            /* If user don't have the customer then create customer */
            if(empty($getUserDetail->customer_id)){
                /* Create stripe customer */
                $customer_id = StripeService::createCustomer(['email'=> $getUserDetail->email]);
                $customerCreated = UserRepository::update(['id'=>$getUserDetail->id], ['customer_id' => $customer_id]); 

                if(empty($customerCreated)){
                    throw new Exception(__('message.something_went_wrong_while_creating_customer'));
                }

            }else{
                /*Get customer from user */
                $customer_id = $getUserDetail->customer_id;
            }

            $request['customer_id'] = $customer_id;
            $createCard = StripeService::saveCard($request);
            
            if(empty($createCard)){
                throw new Exception(__('message.something_went_wrong_while_add_card'));
            }

            $getUserSaveCard = UserCardRepository::findOne(['user_id' =>$getUserDetail->id ,'card_key' => $createCard->fingerprint]);
            /* If card already exist than delete the created card */
            if(!empty($getUserSaveCard)){
                $removeCard = StripeService::removeCard(['customer_id'=> $createCard->customer,'card_id'=> $createCard->id]);
                
                if(empty($removeCard)){
                    throw new Exception(__('message.something_went_wrong_while_add_card'));
                }

                throw new Exception(__('message.card_details_already_exist'));
            }else{
                /* Create new card detail */
                $createCard['email'] = $getUserDetail->email;
                $createSaveCard = self::createCard($createCard);
                
                if(empty($createSaveCard)){
                    throw new Exception(__('message.something_went_wrong_while_add_card'));
                }

                return true;
            } 

        }catch(\Exception $ex){
            throw $ex;
        }
    }

    /**
     * Function is used to make card as default
     * @param Request $request
     */
    public static function makeCardDefault($request)
    {
        DB::beginTransaction();
        try{
            $post = $request->all();
            $getUserDetail = getUserDetail();
            $getCard = self::findOne(['id' => $post['id']]);
            
            if(empty($getCard)){
                throw new Exception(__('message.card_detail_not_found'));
            }

            $getDefaultCard = StripeService::setDefaultCard(['token'=> $getCard->card_id, 'customer_id'=> $getUserDetail->customer_id]);

            if(!empty($getDefaultCard) && !empty($getDefaultCard['id'])){
                $setDefaultCard = UserCard::where(['id' => $post['id'], 'user_id' => $getUserDetail->id])->update(['is_default' => '1']); 
                if(!empty($setDefaultCard)){
                    
                    UserCard::where(['user_id' => $getUserDetail->id])->whereNotIn('card_id', [$getCard->card_id])->update(['is_default' => '0']); 

                    DB::commit();
                    return true;
                }
                DB::rollback();
                throw new Exception(__('message.something_went_wrong_while_making_card_as_default')); 
            } 
            DB::rollback();
            throw new Exception(__('message.something_went_wrong_while_making_card_as_default')); 
  
        }catch(Exception $ex){
            DB::rollback();
            throw $ex;
        }
    }


    /**
     * Function is used to delete save card.
     * @param Request $request
     */
    public static function deleteCard($request)
    {  
        DB::beginTransaction();
        try{
            $post = $request->all();
            
            $getUserDetail = getUserDetail();
            /* Get user active card  */
            $userCardCount = UserCard::where(['user_id' => $getUserDetail->id, 'status' => 'active'])->count();
            
            if(empty($userCardCount)){
                throw new Exception(__('message.no_active_card_found_to_delete')); 
            } 
 
            $getCard = UserCard::with(['cardSubscription' => function($query) use($post) { 
                $query->where('card_id', '=', $post['id'])
                 ->where('status', '=', 'active')
                 ->where('stripe_status', '=', 'active');
            },'cardSubscription.subscription:id,slug'])->where(
                [
                    'status'=> 'active', 
                    'user_id' => $getUserDetail->id, 
                    'id' => $post['id']
                ]
            )->first();
           
            if(empty($getCard)){
                throw new Exception(__('message.card_detail_not_found_to_delete'));
            }

            if($userCardCount > 1 && $getCard->is_default == 1){
                
                throw new Exception('Please make any other payment method as default to deleting this card');
            }

            /* Check if user has only one card left  */
            if($userCardCount == 1){
                // $card_id = Null;

                if(!empty($getCard->cardSubscription) && count($getCard->cardSubscription) > 0){
                    /* Check if cubscription is not a life time plan */
                    foreach($getCard->cardSubscription as $subscriptionRow){

                        if($subscriptionRow->subscription->slug !== 'life_time'){
                            throw new Exception(__('message.card_can_not_be_deleted_because_of_recurring_payment_method'));
                        }

                    }
                }

             } 
            
            /* Remove card from stripe */
            $getRemoveCardResponse =  StripeService::removeCard(
                [
                'customer_id'=> $getUserDetail->customer_id,
                'card_id'=> $getCard->card_id
                ]);
            
            if(!empty($getRemoveCardResponse) && !empty($getRemoveCardResponse['id'])){
                $getCard->status = 'deleted';
                $getCard->save();
                DB::commit();
                return true; 
            }
            
            throw new Exception(__('message.something_went_wrong_while_deleting_card_detail'));
            DB::rollback();
           
        }catch(Exception $ex){
                DB::rollback();
                throw $ex;
        }
        
    }  
    
    /**
     * Get user save card with susbcription.
     * @param array $where
     */
    public static function getSaveCardWithSubscription()
    {
        try{
            $userId = getUserDetail()->id;
            return UserCard::with('cardSubscription')->where(['status'=> 'active', 'user_id' => $userId])->where('status', 'active')->orderBy('is_default', 'ASC')->get();
        }catch(Exception $ex){
            throw $ex;
        }
    }

        
    /**
     * Delete card using webhook
     * @param array $where
     */
    public static function webhookDeleteCard($request)
    {
        try{
            if(!empty($request)){
                $getCard = UserCard::with('user')->where('card_id', $request->id)->where(function ($query) {
                    $query->Where('status', '=', 'active')
                          ->orWhere('status', '=', 'deleted');
                })->first();
               
                if(empty($getCard)){
                    return 'Card not found to delete';
                }

                $getCustomer = StripeService::getCustomer(['customer_id' => $getCard->user->customer_id]);
                $cardId = null;
                if(!empty($getCustomer) && !empty($getCustomer->default_source)){
                    $getDefaultCard = UserCard::with('user')->where('card_id', $getCustomer->default_source)->first();
                    if(!empty($getDefaultCard)){
                        
                        $getDefaultCard->is_default = '1';
                    
                        if($getDefaultCard->save()){
                            $cardId = $getDefaultCard->id;
                        }
                       
                    }
                }

                /*Get subscription by card id*/
                $getCardSubscription = ProfileSubscriptionRepository::getSubscriptionByCardId(['card_id'=> $getCard->id]);
                
                if(!empty($getCardSubscription) && count($getCardSubscription) > 0){
            
                    foreach($getCardSubscription as $subscription){
                        ProfileSubscriptionRepository::updateSubscriptionCard($subscription->id, $cardId);
                    }
                }

                $deleteStatus = UserCard::where('card_id', $request->id)->delete();    

                if($deleteStatus){    
                    return 'Card deleted by webhook'. $request->id;
                }else{
                    return 'Card not deleted by webhook'. $request->id;
                }
            }

        }catch(Exception $ex){
            throw $ex;
        }
    }

    /**
     * Make card default using webhook
     * @param array $where
     */
    public static function webhookCustomerDefaultCard($request)
    {
        try{
            if(!empty($request)){
                $getCard = UserCard::where('card_id', $request->default_source)->first();
                $getCard->is_default = '1';
                $getCard->save();
                if($getCard){
                    
                    $getAllCard = UserCard::where('user_id', $getCard->user_id)->get();
                    
                    if(!empty($getAllCard) && count($getAllCard) == 1){
                        $getSubscription = ProfileRepository::getProfileWithActiveSubscriptionByUserId(['user_id'=>$getCard->user_id]);
                    
                        if(!empty($getSubscription) && count($getSubscription) > 0){
                            
                            foreach($getSubscription as $profile){
                                if(!empty($profile)){
                                    
                                    foreach($profile->ProfileSubscription as $row){
                                        ProfileSubscriptionRepository::updateSubscriptionCard($row->id, $getCard->id);
                                        Log::debug('updatedSubscription', ['updatedSubscription'=> 'updatedSubscription']);
                                    }

                                }
                            }
                        }
                      
                    }
                   
                    UserCard::where(['user_id' => $getCard->user_id])->whereNotIn('card_id', [$request->default_source])->update(['is_default' => '0']); 

                    return 'Card set to default'. $request->default_source;
                }else{

                    return 'Card not maked default'. $request->default_source;
                }
            }
            return 'Card not found to make default.';

        }catch(Exception $ex){
            throw $ex;
        }
    }
}
