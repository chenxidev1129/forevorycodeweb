<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AccessAccountRequest;
use App\Repositories\UserRepository;
use App\DataTables\AccessAccountsDataTable;
use Exception;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class AccessController extends Controller
{
    /**
     * @var accessAccountsDataTable
     */
    private $accessAccountsDataTable;
    
    public function __construct(AccessAccountsDataTable $accessAccountsDataTable)
    {

        $this->accessAccountsDataTable = $accessAccountsDataTable;
    }

     /**
     * Display a listing accounts.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(){

        return $this->accessAccountsDataTable->render('admin.access.index');
    
     }

    
    /**
     * Load add or edit product form.
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $getAccount = [];
        if(!empty($request->id)){
            $getAccount = UserRepository::findOne(['id'=>$request->id]);
            if(empty($getAccount)){
                return response()->json(
                    [
                        'success' => false,
                        'data' => [],
                        'message' => __('message.account_not_found')
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }
        $html = View::make('admin.access.modal.load-account-form', compact('getAccount'))->render();
        return response()->json(
            [
                'success' => true,
                'html' => $html
            ],
            Response::HTTP_OK
        );
    }

    
    /**
     * Add product information.
     * @param  \Illuminate\Http\Request  $AccessAccountRequest
     * @return \Illuminate\Http\Response
     */
    public function store(AccessAccountRequest $request)
    {
        try{
            UserRepository::addAccessAccount($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => [],
                    'message' => __('message.account_added_success')
                ],
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
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
     * Update access account information.
     * @param  \Illuminate\Http\Request  $AccessAccountRequest
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccessAccountRequest $request, $id)
    {
        try{
            UserRepository::updateAccessAccount($request ,$id);
            return response()->json(
                [
                    'success' => true,
                    'data' => [],
                    'message' => __('message.account_update_success')
                ],
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
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
     * Function is used to update the status from active to inactive .
     *  @param Illuminate\Http\Request $request
     *  @param $id
     *  @return \Illuminate\Http\Response
     */
    public function updateAccessAccountStatus(Request $request, $id)
    {
        try {
            UserRepository::updateAccessAccountStatus($request, $id);
            return response()->json(
                [
                    'success' => true,
                    'data' => [],
                    'message' => __('message.udpate_account_status_success')
                ],
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
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
     * Function used to get security level
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getSecurity(Request $request){
        try {
     
            $html = View::make('admin.access.modal.load-security-level', compact('request'))->render();
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
                    'data' => '',
                    'message' => $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

}
