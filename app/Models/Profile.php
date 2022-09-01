<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'profile_name', 'date_of_birth', 'date_of_death', 'short_description', 'profile_image', 'journey', 'status', 'terms_condition', 'is_saved', 'qrcode_image', 'family_tree', 'gender', 'shared_link', 'prodigy_status','purchase_type'
    ];
    
    /* Convert the first character of to uppercase */
    public function getProfileNameAttribute($value){
        if($value){
          return ucfirst($value);
        }
        return $value;
    }
    
    /**
     * Get the profile media images.
     */
    public function profileMediaImage(){

        return $this->hasMany('App\Models\ProfileMedia', 'profile_id', 'id')->where('type','image')->where('status','active')->orderBy('position','asc');
    }
    
    /**
     * Get the profile media videos.
     */
    public function profileMediaVideo(){

        return $this->hasMany('App\Models\ProfileMedia', 'profile_id', 'id')->where('type','video')->where('status','active')->orderBy('position','asc');
    }

    /**
     * Get the profile media audio.
     */
    public function profileMediaAudio(){

        return $this->hasMany('App\Models\ProfileMedia', 'profile_id', 'id')->where('type','audio')->where('status','active');
    }
    
    /**
     * Get the profile stories and articles.
     */
    public function ProfileStoriesArticle(){

        return $this->hasMany('App\Models\ProfileStoriesArticle', 'profile_id', 'id')->where('status', 'active')->orderBy('position','asc');
    }  
    
    /**
     * Get the profile grave site.
     */
    public function ProfileGraveSite(){

        return $this->belongsTo('App\Models\ProfileGraveSite',  'id', 'profile_id');
    }  

    /**
     * Get profile subscription.
     */
    public function ProfileSubscription(){

        return $this->hasMany('App\Models\ProfileSubscription',  'profile_id', 'id')->orderBy('id','desc');
    }  

    /**
     * Get profile user.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Get the profile latest active subscription.
     */
    public function profileLatestSubscription(){

        return $this->hasOne('App\Models\ProfileSubscription', 'profile_id', 'id')->latest('id');
    } 
}
