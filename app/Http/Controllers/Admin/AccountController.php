<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\DataTables\AccountsDataTable;
use App\Repositories\UserRepository;
use App\Http\Requests\EditAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use Exception;
class AccountController extends Controller
{

    /**
     * @var accountsDataTable
     */
    private $accountsDataTable;

    
    public function __construct(AccountsDataTable $accountsDataTable)
    {

        $this->accountsDataTable = $accountsDataTable;
    }

    /**
     * Show account list page
     * @return view 
     */

    public function index(){
        
        return $this->accountsDataTable->render('admin.accounts.index');
    }

    /**
     *  Load edit subscription form.
     * @return \Illuminate\Http\Response
     */
    public function loadEditAccount(Request $request)
    {
        $getUser = [];
        if(!empty($request->id)){
            $getUser = UserRepository::findOne(['id'=>$request->id]);            
            if(empty($getUser)){

                return response()->json(
                    [
                        'success' => false,
                        'message' => __('User not found')
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }

        $html = View::make('admin.accounts.modal.load-edit-account', compact('getUser'))->render();
        return response()->json(
            [
                'success' => true,
                'html' => $html
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Function is used to update the status from active to inactive .
     *  @param Request $request
     *  @param $id
     *  @return \Illuminate\Http\Response
     */
    public function updateAccountStatus(Request $request, $id)
    {
        try {
            UserRepository::updateUserAccountStatus($request, $id);
            return response()->json(
                [
                    'success' => true,
                    'id' => $id,
                    'message' => __('message.udpate_account_status_success')
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
     * Function uset to update user account detail .
     *  @param EditAccountRequest $request
     *  @return \Illuminate\Http\Response
     */
    public function editAccount(EditAccountRequest $request)
    {
        try {
            UserRepository::editAccount($request);
            return response()->json(
                [
                    'success' => true,
                    'message' => __('message.account_update_success')
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
     * Show profile detail list 
     * @param id
     * @return view 
     */
    public function profileDetails($id){
       
        $userDetail =  UserRepository::getProfileUser($id);
        return view('admin.accounts.profile-details',compact('userDetail'));
    }    

    /**
     *  Load user profile.
     * @param @request 
     * @return \Illuminate\Http\Response
     */
    public function loadUserProfile(Request $request)
    {
        try{

            $getUserProfile = UserRepository::getUserCreatedProfile($request);
            if(!empty($getUserProfile) && count($getUserProfile->profile) > 0){
                $html = View::make('admin.accounts.modal.load-user-profile', compact('getUserProfile'))->render();
                return response()->json(
                    [
                        'success' => true,
                        'html' => $html
                    ],
                    Response::HTTP_OK
                );
            }else{
                return response()->json(
                    [
                        'success' => true,
                        'html' => '<div class="w-100 px-3"><div class="alert alert-danger">No record found</div></div>'
                    ],
                    Response::HTTP_OK
                );
            }
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
}
