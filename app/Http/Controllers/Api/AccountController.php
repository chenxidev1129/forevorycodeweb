<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\Api\EditAccountRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
   /**
    * Function used to update user account 
    * @param EditAccountRequest $request 
    * @return $getUser
    */
    
    public function editAccount(EditAccountRequest $request){
        try {

            $getUser = UserRepository::editAccountDetail($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getUser,
                    'message' => __('message.profile_update_successfully')
                ],
                Response::HTTP_OK
            );
        
        } catch (\Exception $ex) {
            return response()->json(
                [
                    'success' => false,
                    'data' => "",
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    } 
    

    /**
     * Function used to update password
     * @param ChangePasswordRequest $request 
     * @return json
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
     * Log the user out (Invalidate the token).
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout(Request $request)
    {
        try {
            UserRepository::logout($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Logout successful.'
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
     * Get setting key.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSetting(Request $request)
    {
        try {
            return response()->json(
                [
                    'success' => true,
                    'data' =>  [
                        'stripe_key'=> config('services.stripe.secret_key'),
                        'prodigi_key'=> config('constants.Prodigi.REQUEST_TOKEN')
                    ],
                    'message' => __('message.success')
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
     * Get login user account detail.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAccountDetail(Request $request)
    {
        try {
            $getAccountDetail = UserRepository::getUserProfile($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $getAccountDetail,
                    'message' => __('message.success')
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
