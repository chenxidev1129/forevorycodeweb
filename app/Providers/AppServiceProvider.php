<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(config("constants.APP_ENV") !== 'local') {
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if(config("constants.APP_ENV") !== 'local') {
            URL::forceScheme('https');
        }

        /* check unique email validation.  */
        Validator::extend('check_email', function ($attribute, $value, $parameters, $validator) {
            $post = request()->all();  
            $userData = getUserDetail();
          
            $userId = !empty($userData) ?  ($userData->user_type == 'admin' ? (!empty($post['id']) ? $post['id'] : '') : $userData->id ) : (!empty($post['id']) ? $post['id'] : '');
          
            if (!empty($userId)) {
                $userInfo = User::where(['email' => strtolower($value)])->where('status', '!=', 'deleted')->where('id', '!=', $userId)->first();
                
            }else{
                $userInfo = User::where(['email' => strtolower($value)])->where('status', '!=', 'deleted')->first();
            }
            
            if (!empty($userInfo)) {
                return false;
            } else {
                return true;
            }
        });

        /* check phone formate */
        Validator::extend('check_phone_format', function ($attribute, $value, $parameters, $validator) {
            $post = request()->all();
            if(!empty($post) && $post['phone_number']){
                $number = preg_replace('/[^0-9]/', '', $post['phone_number']); 
                if(strlen($number) != 10){
                    return false;
                }
            }
            return true;
        });      
        
        /* check unique phone validation. */
        Validator::extend('check_phone', function ($attribute, $value, $parameters, $validator) {
            $post = request()->all();  
            $number = preg_replace('/[^0-9]/', '', $post['phone_number']); 
            $userData = getUserDetail();

            $userId = !empty($userData) ?  ($userData->user_type == 'admin' ? (!empty($post['id']) ? $post['id'] : '') : $userData->id ) : (!empty($post['id']) ? $post['id'] : '');
            
            if (!empty($userId)) {
                $userInfo = User::where(['phone_number' => $number])->where('status', '!=', 'deleted')->where('id', '!=', $userId)->first();
            }else{
                $userInfo = User::where(['phone_number' => $number])->where('status', '!=', 'deleted')->first();
            }
            if (!empty($userInfo)) {
                return false;
            } else {
                return true;
            }
        });  

        /* check card month and year validation */
        Validator::extend('card_exp_date', function ($attribute, $value, $parameters, $validator) {
            $post = request()->all();  
            if(!empty($post['exp_date'])){
                $exp_date = explode("/",$post['exp_date']);
            
                if(empty($exp_date[0]))
                return false;

                if(12 >= $exp_date[0]){
                
                    if(empty($exp_date[1]))
                    return false;

                    if(date("y") <= $exp_date[1]){

                        return true;
                    }

                    return false;    
                }

                return false;
            }
        }); 
    }
}
