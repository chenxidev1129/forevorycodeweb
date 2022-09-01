<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\SignUpRequest;
use App\Http\Requests\Api\OtpVerifyRequest;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\ForgotPasswordResetRequest;
use App\Http\Requests\Api\SocialLoginRequest;
use App\Http\Requests\Api\ResendOtpRequest;
use App\Http\Requests\Api\PasswordOtpVerificationRequest;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
   
    /**
     * User Login
     * @param LoginRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {  
        try {
            $getAuthUser = UserRepository::appLogin($request);
            /* Check for verified user */
            $message = (!empty($getAuthUser) && $getAuthUser->email_verified == 1) ? __('message.login_successful') : __('message.email_not_verify_message');
            return response()->json(
                [
                    'success' => true,
                    'data' => $getAuthUser,
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
     * Function used to sign up
     * @param SignUpRequest 
     * @return \Illuminate\Http\JsonResponse
     */
      
    public function signUp(SignUpRequest $request){
        try {
            $getEmail = UserRepository::appSignUp($request);
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
     * Function used to otp verification
     * @param OtpVerifyRequest  
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function otpVerification(OtpVerifyRequest $request){
        try {
            $getUser = UserRepository::appOtpVerification($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getUser,
                    'message' => __('message.login_successful')
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
     * Function used for social login
     * @param SocialLoginRequest  
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function socialLogin(SocialLoginRequest $request){
    
        try {
            $getUser = UserRepository::appSignUpSocial($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getUser,
                    'message' => __('message.login_successful')
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
     * Function used for send forgot password otp
     * @param ForgotPasswordRequest  
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request){
       
        try {
                UserRepository::sendForgotPasswordOtp($request);
                return response()->json(
                    [
                        'success' => true,
                        'message' => __('message.otp_send_to_email')
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
     * Function used for forgot password otp verification
     * @param PasswordOtpVerificationRequest  
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function forgotPasswordOtpVerification(PasswordOtpVerificationRequest $request){
    
        try {
            UserRepository::forgotPasswordOtpVerification($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.otp_verified_successfully'),
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
     * Function used to reset forgot password
     * @param ForgotPasswordResetRequest $request 
     * @return \Illuminate\Http\JsonResponse
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
     * Function used to resend otp.
     * @param ResendOtpRequest $request 
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOtp(ResendOtpRequest $request){
    
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
}
