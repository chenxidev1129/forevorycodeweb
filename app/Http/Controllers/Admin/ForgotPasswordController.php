<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\ForgotPasswordRequest;
use App\Repositories\PasswordResetRepository;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Config;

class ForgotPasswordController extends Controller
{

    /**
     * Show forgot password page
     *
     */

    public function forgotPassword(){
        try {
            return view('admin.forgot-password');
        } catch (\Exception $ex) {
            return redirect()->to('admin')->with(['alert-type'=> 'error','message'=>$ex->getMessage()]);
        }
     }


    /**
     * Forgot password
     * @param ForgotPasswordRequest $request
     * @return Json
     */
    public function forgot(ForgotPasswordRequest $request)
    {
        try {

            UserRepository::sendForgotPasswordEmail($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.email_sent')
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
     * Show password reset form 
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showResetPasswordPage(Request $request){
        try {
            $user = PasswordResetRepository::isVerifyTokenValid($request);

            return view('admin.reset-password', [
                'verify_token' => $user->verify_token
            ]);
            
        } catch (\Exception $ex) {
           $message = [
               'message' => $ex->getMessage(),
               'alert-type'=> 'error'
           ]; 
           return redirect()->to('admin')->with($message);
           
        }
    }

    /** 
     * Reset password 
     * @param ResetPasswordRequest $request
     * @return Json
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
           
            PasswordResetRepository::resetPassword($request);

            return response()->json(
                [
                    'success' => true,
                    'data' => '',
                    'message' => __('message.password_reset')
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
    
}
