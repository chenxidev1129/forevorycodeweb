<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Session;

class LoginController extends Controller
{
    use RedirectsUsers, ThrottlesLogins;

    public $redirectTo = '/admin/dashboard';

    /**
     * Show admin login page
     */

    public function index(){
       return view('admin.login');
    }

    /**
     * Admin login
     * @param LoginRequest $request 
     * @return \Illuminate\Http\Response 
     */
    public function login(LoginRequest $request){
       
     try {
            $result = UserRepository::adminLogin($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $result,
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
     * Logout the user out of the account.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->to('admin');
       
    }

    /**
     * Get the guard to be used during authentication.
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin-web');
    }

    /**
     * Change password view
     * @return view
     */
     public function changePassword(){
         return view('admin.change-password');
     }

    /**
     * change password
     * @param Request $request
     * @return \Illuminate\Http\Response 
     */
    public function changePasswordPost(ChangePasswordRequest $request)
    {
        try {
            UserRepository::changePassword($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => [],
                    'message' => __('message.password_update_success')
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
