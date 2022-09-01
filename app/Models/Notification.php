<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id', 'user_id', 'title', 'message', 'type', 'is_read', 'status'
    ];

    protected $appends = ['notifcation_time'];  

    /**
     * Attribute to convert created date into local time zone
     */
    public function getNotifcationTimeAttribute(){
        $timezone = getRequestTimezone();
        if(!empty($timezone)) {
            $checkDate = convertTimezone($this->created_at, 'UTC', $timezone);
            return $checkDate->format('Y-m-d H:i:s');
        }
    }
    
}
