<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'card_id', 'card_name', 'last_digit', 'email', 'is_default', 'card_type', 'card_key', 'status','exp_month', 'exp_year'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'card_key'
    ];

    /**
     * Get user card subscription.
     */
    public function cardSubscription(){

        return $this->hasMany('App\Models\ProfileSubscription',  'card_id', 'id')->orderBy('id','desc');
    }  

    /**
     * Get the susbscription renew plan.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->select(['id','first_name', 'last_name','customer_id']);
    } 
}
