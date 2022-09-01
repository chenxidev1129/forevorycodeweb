<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Config;

use Closure;

class AdminAuthenticate
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
        if(!Auth::guard('admin-web')->check()){

            return redirect()->to('admin');
        }
        //Check if user is deactivated.
        $user = Auth::guard('admin-web')->user();
        if($user->status != 'active'){

            Auth::guard('admin-web')->logout();
            $request->session()->invalidate();

            $message = [
                'message' => __('message.access_account_inactive'),
                'alert-type'=> 'error'
            ]; 
            return redirect()->to('admin')->with($message);
    
        }

        $request->request->add(['guard' => 'admin-web']);
        $response = $next($request);

        return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }
}
