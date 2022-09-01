<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileMedia extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id', 'type', 'media', 'caption', 'position', 'duration', 'thumbnail', 'user_id'
    ];

    protected $appends =['media_with_url','media_thumbnail'];     
    /* Convert the first character of to uppercase */
    public function getCaptionAttribute($value){
        if($value){
          return ucfirst($value);
        }
        return $value;
    }

    /* Added media url */
    public function getMediaWithUrlAttribute(){
        if($this->media){
            return getUploadMedia($this->media);
        }else{
            return false;
        }
    }

    /* Added media url */
    public function getMediaThumbnailAttribute(){
        if($this->thumbnail){
            return getUploadMedia($this->thumbnail);
        }else{
            return false;
        }
    }

    
    /**
     * Get profile of article
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->select(['id', 'first_name', 'last_name', 'image']);
    }

    /**
     * Get profile of article
     */
    public function mediaSubscription()
    {
        return $this->belongsTo('App\Models\ProfileSubscription', 'profile_id', 'profile_id')->where('status', 'active')->select(['id', 'status']);
    }

    /**
     * Get profile
     */
    public function profile()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id')->select(['id','user_id', 'profile_name']);
    }
}
