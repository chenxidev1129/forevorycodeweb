<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\UserSignUpRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ForgotPasswordResetRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\OtpVerify;
use App\Http\Requests\EditAccountRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class LoginController extends Controller
{
    /**
     * Function used to show login view.
     * @param profileId
     * @return \Illuminate\Http\Response  
     */

    public function index($profileId= ''){

      return view('user.login' ,compact('profileId'));

    }

    /**
     * Function used to show forgot-password view. 
     * @return \Illuminate\Http\Response 
     */
    public function forgotPassword(){

        return view('user.forgot-password');
    } 
    
    /**
     * Function used to otp verification
     * @param OtpVerify $request 
     * @return \Illuminate\Http\Response
     */
    public function otpVerification(OtpVerify $request){
    
        try {
            UserRepository::otpVerification($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.login_successful')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used to show sign up view.
     * @param profileId
     * @return \Illuminate\Http\Response 
     */
     public function signUp($profileId= ''){

        return view('user.sign-up', compact('profileId'));
     }

     /**
      * Function used to create use sign up.
      * @param UserSignUpRequest $request 
      * @return \Illuminate\Http\Response
      */
    public function signUpCreate(UserSignUpRequest $request){
        
        try {
            $getEmail = UserRepository::signUpCreate($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getEmail,
                    'message' => __('message.sign_up_success')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used for user login.
     * @param LoginRequest $request 
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request){
       
        try {
            $getUser = UserRepository::userLogin($request);
            if(!empty($getUser) && $getUser->email_verified == 1){
                $message = __('message.login_successful');
            }else{
                $message = __('message.email_not_verify_message');
            }
            return response()->json(
                [
                    'success' => true,
                    'data' => $getUser,
                    'message' => $message
                ],
                Response::HTTP_OK
            );
             
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Function used to resend otp.
     * @param Request $request 
     * @return \Illuminate\Http\Response
     */
    public function resendOtp(Request $request){
    
        try {
            UserRepository::resendOtp($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.otp_send')
                ],
                Response::HTTP_OK
            );
            
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }   
           
    /**
     * Function used for send forgot password otp.
     * @param forgotPasswordRequest $request 
     * @return \Illuminate\Http\Response
     */
    public function forgotPasswordRequest(ForgotPasswordRequest $request){
       
        try {
            $getUserEmail = UserRepository::sendForgotPasswordOtp($request);
            return response()->json(
                [
                    'success' => true,
                    'email' => $getUserEmail,
                    'message' => __('message.otp_send_to_email')
                ],
                Response::HTTP_OK
            );
             
        } catch (\Exception $ex) {
            return response()->json(
                [    
                    'success' => false,
                    'email' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }    
    
    /**
     * Function used for forgot password otp verification
     * @param OtpVerify $request 
     * @return \Illuminate\Http\Response
     */
    public function forgotPasswordOtpVerification(OtpVerify $request){

        try {
            $getUser = UserRepository::forgotPasswordOtpVerification($request);
            return response()->json(
                [
                    'success' => true,
                    'email' => $getUser,
                    'message' => __('message.otp_verified_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'email' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    
    /**
     * Function used to reset forgot password
     * @param ForgotPasswordResetRequest $request 
     * @return \Illuminate\Http\Response
     */
    public function resetForgotPassword(ForgotPasswordResetRequest $request){
        try {
            UserRepository::resetForgotPassword($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.password_change_successfully'),
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }     
    
    /**
     * Function used to show change password view.
     * @return \Illuminate\Http\Response  
     */
    public function changePassword(){
        return view('user.change-password');  
    }

    /**
     * Function used to update account password.
     * @param ChangePasswordRequest $request 
     * @return \Illuminate\Http\Response
     */
    public function changePasswordRequest(ChangePasswordRequest $request){
        try {
            UserRepository::changePassword($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.password_change_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }  

    /**
     * Function is used to show edit account detail.
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function editAccount(Request $request){
    
        try {
            $getUser = UserRepository::getUserProfile($request);
            return view('user.edit-account',compact('getUser'));

        } catch (Exception $ex) {
            return redirect()->route('profile')->with(
                [
                'alert-type'=> 'error',
                'message'=>$ex->getMessage()
                ]
            );
        }
    }  
    
    /**
     * Function used to update user account 
     * @param EditAccountRequest $request 
     * @return \Illuminate\Http\Response
     */  
    public function editAccountDetail(EditAccountRequest $request){
        try {
            UserRepository::editAccountDetail($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.profile_update_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }    

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user-web');
    }

    /**
     * Logout the user out of the account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect('/');
       
    }   
         
    /**
     * Load guest sign up form.
     * @return \Illuminate\Http\Response
     */
    public function loadGuestSignUp(Request $request)
    {
        try {
            $html = View::make('user.profile.guest.load-sign-up')->render();
            return response()->json(
                [
                    'success' => true,
                    'html' => $html
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'html' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     *  Load guest login form.
     * @return \Illuminate\Http\Response
     */
    public function loadGuestLogin(Request $request)
    {
        try {
            $html = View::make('user.profile.guest.load-login', compact('request'))->render();
            return response()->json(
                [
                    'success' => true,
                    'html' => $html
                ],
                Response::HTTP_OK
            );
        
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'html' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }    
}
