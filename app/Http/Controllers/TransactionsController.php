<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\SubscriptionUserDataTable;
use App\DataTables\UserTransectionDataTable;
use App\Repositories\ProfileSubscriptionRepository;
use App\Repositories\SubscriptionPlanRepository;
use App\Repositories\UserCardRepository;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class TransactionsController extends Controller
{
    
    /**
     * @var subscriptionUserDataTable
     * @var userTransectionDataTable
     */
    private $subscriptionUserDataTable;
    private $userTransectionDataTable;
    
    public function __construct(SubscriptionUserDataTable $subscriptionUserDataTable, UserTransectionDataTable $userTransectionDataTable)
    {

        $this->subscriptionUserDataTable = $subscriptionUserDataTable;
        $this->userTransectionDataTable = $userTransectionDataTable;
    }

    /**
     * Function to show transection index.
     * @return \Illuminate\Http\Response 
     */

    public function index(){
        return view('user.transaction.index');
     }

    /**
     * Function to show subscription list.
     * @return \Illuminate\Http\Response 
     */

    public function subscriptionListing(){
        return $this->subscriptionUserDataTable->render('datatable'); 
     }


    /**
     *  Get subscription detail.
     * @return \Illuminate\Http\Response
     */
    public function subscriptionDetail(Request $request)
    {
        $getSubscriptionDetail = [];
        if(!empty($request->id)){
            $getSubscriptionDetail = ProfileSubscriptionRepository::findOne(['id'=>$request->id], ['subscription','profile']);
       
            $getDefaultPlan = SubscriptionPlanRepository::findOne(['status'=> 'active', 'id' =>  $getSubscriptionDetail->plan_id]);
           
            if(empty($getSubscriptionDetail)){
                return response()->json(
                    [
                        'success' => false,
                        'data' => [],
                        'message' => __('message.subscription_detail_not_found')
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }
        $html = View::make('user.transaction.modal.load-subscription-details', compact('getSubscriptionDetail','getDefaultPlan'))->render();
        return response()->json(
            [
                'success' => true,
                'html' => $html
            ],
            Response::HTTP_OK
        );
    }     
  
    /**
     *  Get subscription plan.
     * @return \Illuminate\Http\Response
     */
    public function viewSubscriptionPlan(Request $request)
    {
        $getSubscriptionDetail = $getCurrentSubscriptionDetail = [];
        if(!empty($request->id)){
           
            $getCurrentSubscriptionDetail = ProfileSubscriptionRepository::findOne(['id'=> $request->id], ['subscription']);
            $getSubscriptionDetail = SubscriptionPlanRepository::getSubscriptionPlan(['status'=> 'active'], [$getCurrentSubscriptionDetail->subscription->slug,'free_trial']);
            $getDefaultPlan = SubscriptionPlanRepository::findOne(['status'=> 'active', 'id' =>  $getCurrentSubscriptionDetail->plan_id]);
            $getAllPlan = SubscriptionPlanRepository::getSubscriptionPlan(['status'=> 'active'], ['free_trial']);        
                
            if(empty($getCurrentSubscriptionDetail)){
                return response()->json(
                    [
                        'success' => false,
                        'data' => [],
                        'message' => __('message.subscription_detail_not_found')
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }
        $html = View::make('user.transaction.modal.load-subscription-plan', compact('getCurrentSubscriptionDetail', 'getSubscriptionDetail','getAllPlan','getDefaultPlan'))->render();
        return response()->json(
            [
                'success' => true,
                'html' => $html
            ],
            Response::HTTP_OK
        );
    } 

    /**
     * Function to show transection list.
     * @return \Illuminate\Http\Response 
     */
    public function transectionList(){
        return $this->userTransectionDataTable->render('datatable'); 
    }

    /**
     *  Load payment method.
     * @return \Illuminate\Http\Response
     */
    public function loadManagePayment(Request $request)
    {
        $getSaveCard = UserCardRepository::getSaveCardWithSubscription();
      
        $html = View::make('user.transaction.modal.load-manage-payment', compact('getSaveCard'))->render();
        return response()->json(
            [
                'success' => true,
                'html' => $html
            ],
            Response::HTTP_OK
        );
    }    
}
