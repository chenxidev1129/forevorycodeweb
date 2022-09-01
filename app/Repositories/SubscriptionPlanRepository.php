<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Services\StripeService;

class SubscriptionPlanRepository{

    /**
     * Find one
     * @param array $where
     * @param array $with
     * @return  Subscription
     */
    public static function findOne($where, $with = [])
    {
        return SubscriptionPlan::with($with)->where($where)->first();
    }

    /**
     * Get all active subscription plan
     * @param array $where
     * @return  Subscription
     */
    public static function getSubscriptionPlan($where, $slug=array())
    {
        try{
            $subscriptionPlan = SubscriptionPlan::select('id','price','plan','days','slug','stripe_price_id','apple_product_id','apple_price','status','created_at','updated_at', DB::raw("IF(slug = 'life_time', '(Free trial not applicable)', '') as optional_text"))->where($where)->whereNotIn('slug', $slug)->get();
           
            if(!empty($subscriptionPlan)){
                return $subscriptionPlan;
            }
            throw new Exception(__('message.subscription_plan_details_not_found'));

        } catch (\Exception $ex) {
            throw $ex;
        }    
    }

    /**
    * Function used to update subscription plan.
    * @param Request $request
    * @param int $id
    * @return boolean
    * @throws Exception
    */
    public static function updateSubscriptioPlane($request,$id){
        try{
            $post = $request->all();
            $price = SubscriptionPlan::where('id',$id)->where('status','active')->first();

            if(!empty($price)) {
                
                if(!empty($post['slug']) && $post['slug'] == 'free_trial'){
                    $update['days'] = $post['days'];
                    $result = SubscriptionPlan::where('id',$id)->update($update);
                }else{

                    if($post['price'] != $price->price) {

                        if($post['slug'] == 'life_time') {
                            $update['price'] = $post['price'];
                            $result = SubscriptionPlan::where('id',$id)->update($update);
                        } else {
                            $data = [
                                'price_id' => $price->stripe_price_id,
                                'amount' => $post['price']
                            ];
                            $newPrice = StripeService::createPrice($data);

                            if(!empty($newPrice['id'])) {
                                $update['price'] = $post['price'];
                                $update['stripe_price_id'] = $newPrice['id'];
                                $result = SubscriptionPlan::where('id',$id)->update($update);
                            }
                        }
                    }
                }

                return true;
            }
            throw new Exception(__('message.something_went_wrong_while_updating_subscription_details'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

   /**
    * Get subscription price
    * @param Request $request
    * @return array
    */
    public static function getSubscriptionPlanPrice($request)
    {
        try{
            $post = $request->all();
            $subscriptionPrice = Self::findOne(['id' => $post['id']]);
            if(!empty($subscriptionPrice)){
                return $subscriptionPrice;
            }
            throw new Exception(__('message.subscription_plan_details_not_found'));
        } catch (\Exception $ex) {
            throw $ex;
        }    
    }

   /**
    * Get profile switch plan.
    * @param Request $request
    * @return array
    */
    public static function getSwitchPlan($request){
        try{
            $post = $request->all();

            $getSwitchPlan = SubscriptionPlan::select('subscription_plans.id','subscription_plans.slug' ,'subscription_plans.plan', 'subscription_plans.price', 'current_plan.subscription_price', DB::raw("IF(current_plan.purchase_plan_id > 0, 'active', 'inactive') as status"))
           
            ->leftjoin('profile_subscriptions as current_plan', function ($join) use ($post) {
                $join->on('current_plan.purchase_plan_id', '=', 'subscription_plans.id')->where('current_plan.id', '=', $post['id']);
            })->whereNotIn('subscription_plans.slug', ['free_trial'])->where('subscription_plans.status', 'active')->get();
           
            if(!empty($getSwitchPlan)){
                return $getSwitchPlan;
            } 
            
            throw new Exception(__('message.subscription_switch_plan_details_not_found'));

        }catch(\Exception $ex){
             throw $ex; 
        }
    } 

    /**
     * Get all active subscription plan
     * @return array
     */
    public static function getAllSubscriptionPlan()
    {
        try{
            return  SubscriptionPlan::select('id','plan','slug')->where('status','active')->whereNotIn('slug', ['free_trial'])->get();

        } catch (\Exception $ex) {
            throw $ex;
        }    
    }
}
