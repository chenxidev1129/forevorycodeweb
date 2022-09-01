<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','user_type','email_verified','	verify_token','status', 'email', 'password' ,'country', 'state', 'city', 'country_code', 'phone_number', 'address', 'zip_code', 'otp', 'image', 'facebook_id', 'google_id', 'apple_id', 'login_type', 'profile_status', 'lat', 'lng', 'country_short_name','customer_id', 'country_iso_code', 'otp_expires_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'otp', 'facebook_id', 'google_id', 'apple_id', 'verify_token','customer_id', 'otp_expires_at' 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends =['image_url']; 

    /* Added url in image */
    public function getImageUrlAttribute(){
        if($this->image){
            return getUploadMedia($this->image);
        }else{
            return false;
        }
    }
        
    public function profile(){

        return $this->hasMany('App\Models\Profile', 'user_id', 'id');
    }    

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id'=> $this->id,
          ];
    }  
}
