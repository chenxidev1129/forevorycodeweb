<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class UserEditAccountAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Redirect if profile is not completed.
        $user = Auth::guard('user-web')->user();
         if($user->profile_status != '1'){
           
            $message = [
                'message' => __('message.update_profile_message'),
                'alert-type'=> 'error'
            ]; 
            return redirect()->to('edit-account')->with($message);
        }
        return $next($request);
    }
}
