<?php

namespace App\Http\Controllers;
use App\Repositories\UserRepository;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class GoogleLoginController extends Controller
{

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($id)
    {
        if(!empty($id)){
            Session::put('profileId', $id);    
        }
        return Socialite::driver('google')->redirect();
    }   

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            UserRepository::signUpSocial($user->user,'google');
            
            if (!empty(Session::get('profileId'))) {
                return redirect()->route('guest-profile', [Session::get('profileId')]);
            }
            
            /* Check profile is completed or not*/
            $userDetail = Auth::guard('user-web')->user(); 
            if(!empty($userDetail)) {
                if($userDetail->profile_status == 1) {
                    return redirect()->route('profile');
                } else {
                    /* when profile is not complete */
                    return redirect()->route('edit-account');
                }
            }
            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/');
        }
    }

}
