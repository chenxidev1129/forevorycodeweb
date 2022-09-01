<?php

namespace App\Http\Middleware;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Closure;

class UserProfile
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $profileId =  $request->profile_id;
        $getProfile = Profile::select('id','status')->where('id', $profileId)->first();
        if(!empty($getProfile) && $getProfile->status == 'inactive'){
            if($request->ajax())
            {
                return response()->json(
                    [
                        'success' => true,
                        'status' => 'inactive',
                    ]
                );
                
            } else {

                if($request->url() && $request->route()->uri == 'upload-record-voice-note'){
                    return response()->json(
                        [
                            'success' => true,
                            'status' => 'inactive',
                        ]
                    );
                }

                if(!Auth::guard('user-web')->check()){
                    return redirect('/');
                }else{
                   return redirect('profile');
                }
            }

        }
        return $next($request);
    }
}
