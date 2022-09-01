<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileSubscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id', 'card_id', 'subscription_id', 'plan_id', 'purchase_plan_id', 'subscription_price', 'start_date', 'end_date', 'free_trial_days', 'free_trial_start', 'free_trial_end', 'status', 'stripe_status', 'purchase_plan_days', 'canceled_by', 'stripe_charge_id','subscriptions_response'
    ];


    /**
     * Get the susbscription renew plan.
     */
    public function subscription()
    {
        return $this->belongsTo('App\Models\SubscriptionPlan', 'purchase_plan_id', 'id');
    } 

    /**
     * Get the susbscription plan.
     */
    public function subscriptionPlan()
    {
        return $this->belongsTo('App\Models\SubscriptionPlan', 'plan_id', 'id');
    } 

    /**
     * Get the susbscription profile.
     */
    public function profile(){

        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    }  

    /**
     * Get the susbscription profile.
     */
    public function userCard(){

        return $this->belongsTo('App\Models\UserCard', 'card_id', 'id');
    } 
    
    /**
     * Attribute to convert created date into local time zone
     */
    public function getCreatedAtAttribute($value){
        $timezone = getRequestTimezone();
        if($value){
            $checkDate = convertTimezone($value, 'UTC', $timezone);
            return $checkDate->format('Y-m-d H:i:s');
        }
        return $value;
    }
    
}
