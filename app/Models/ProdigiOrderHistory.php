<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdigiOrderHistory extends Model
{
    //protected $table = 'prodigi_order_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id', 'order_id', 'reference_id', 'prodigi_response', 'status'
    ];
}
