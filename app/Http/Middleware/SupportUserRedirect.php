<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class SupportUserRedirect
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
        //Redirect support user to access denied.
        $user = Auth::guard('admin-web')->user();
         if($user->user_type == 'support'){

            return redirect()->to('admin/access-denied');
        }
        return $next($request);
    }
}
