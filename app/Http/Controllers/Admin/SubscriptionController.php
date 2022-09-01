<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\DataTables\SubscriptionPlanDataTable;
use App\Repositories\SubscriptionPlanRepository;
use App\Http\Requests\SubscriptionPlan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Support\Facades\View;

class SubscriptionController extends Controller
{
    /**
     * @var subscriptionPlanDataTable
     */
    private $subscriptionPlanDataTable;
    
    public function __construct(SubscriptionPlanDataTable $subscriptionPlanDataTable)
    {

        $this->subscriptionPlanDataTable = $subscriptionPlanDataTable;
    }

    /**
     * Subscriptions listing view page 
     * @return view
     */
     
    public function index(){
        
        return $this->subscriptionPlanDataTable->render('admin.subscriptions.index');

    } 

    /**
     *  Load edit subscription form.
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $getPlan = [];
        if(!empty($request->id)){
            $getPlan = SubscriptionPlanRepository::findOne(['id'=>$request->id]);
            if(empty($getPlan)){
                return response()->json(
                    [
                        'success' => false,
                        'data' => [],
                        'message' => __('message.subscription_plan_not_found')
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }
        $html = View::make('admin.subscriptions.modal.load-plan-form', compact('getPlan'))->render();
        return response()->json(
            [
                'success' => true,
                'html' => $html
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Update subscription plan.
     * @param  \Illuminate\Http\Request  $SubscriptionPlan
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubscriptionPlan $request, $id)
    {
        try{
            SubscriptionPlanRepository::updateSubscriptioPlane($request ,$id);
            return response()->json(
                [
                    'success' => true,
                    'data' => [],
                    'message' => __('message.subscription_plan_update_success')
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
