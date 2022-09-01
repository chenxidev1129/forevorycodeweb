<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class UserAuthentication
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
        if(!Auth::guard('user-web')->check()){
            return redirect('/');
        }

         //Redirect if user account is deactivated.
         $user = Auth::guard('user-web')->user();
         if($user->status != 'active'){
 
             Auth::guard('user-web')->logout();
             $request->session()->invalidate();
 
             $message = [
                 'message' => __('message.access_account_inactive'),
                 'alert-type'=> 'error'
             ]; 
             return redirect()->to('/')->with($message);
     
         }

        $request->request->add(['guard' => 'user-web']);

        $response = $next($request);

        return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }
}
