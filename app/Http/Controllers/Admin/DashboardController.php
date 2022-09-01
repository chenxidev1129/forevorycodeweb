<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProfileSubscriptionRepository;
use App\Repositories\ProfileMediaRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Repositories\SubscriptionPlanRepository;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Exception;

class DashboardController extends Controller
{
   /**
    * Show admin dashboard
    */
   public function index(){
      try {

         /* get active profile count */
         $activeProfileCount = ProfileSubscriptionRepository::activeProfileCount();

         /* get active visitor count */
         $activeVisitorCount = ProfileMediaRepository::activeVisitorCount();

         /* get visitor profile holder count */
         $visitorProfileCount = ProfileRepository::activeVisitorProfileCount();

         /* get new account count */
         $newAccountCount = UserRepository::getAccountCount();

         /* get unsubscribed count */
         $unsubscribedCount = ProfileSubscriptionRepository::getUnsubscribedCount();

         /* Get subscription plan */
         $getSubscriptionPlan = SubscriptionPlanRepository::getAllSubscriptionPlan();


         return view('admin.dashboard.dashboard', compact('activeProfileCount','activeVisitorCount','visitorProfileCount','newAccountCount','unsubscribedCount','getSubscriptionPlan'));
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
    * Get Graph Activity Data
    */
   public function getGraphActivityData(Request $request){
      try {
          $post = $request->all();

          /* Default date is year */
          $start = Carbon::now()->startOfYear()->format('Y-m-d');
          $end = Carbon::now()->format('Y-m-d');

          if($post['limit']== 'week'){
            $start = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end = Carbon::now()->endOfWeek()->format('Y-m-d');
         }

         if($post['limit']== 'month'){
            $start = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
         }
       
         if($post['limit']== 'dateFilter'){
            $start = getDBFormatDate($post['startDate']);
            $end = getDBFormatDate($post['endDate']);
         }

          /* get active profile count */
          $activeProfileCount = ProfileSubscriptionRepository::activeProfileCount($request, $start, $end);
          
          /* get active visitor count */
          $activeVisitorCount = ProfileMediaRepository::activeVisitorCount($request, $start, $end);
 
          /* get visitor profile holder count */
          $visitorProfileCount = ProfileRepository::activeVisitorProfileCount($request, $start, $end);
 
          /* get new account count */
          $newAccountCount = UserRepository::getAccountCount();
 
          /* get unsubscribed count */
          $unsubscribedCount = ProfileSubscriptionRepository::getUnsubscribedCount($request, $start, $end);

         $result = ProfileSubscriptionRepository::getGraphActivityData($request);
        
         return response()->json(
            [
               'success' => true,
               'data' => ['graphData'=>$result, 'activeProfileCount'=>$activeProfileCount, 'activeVisitorCount'=>$activeVisitorCount, 'visitorProfileCount'=>$visitorProfileCount, 'newAccountCount'=>$newAccountCount, 'unsubscribedCount'=>$unsubscribedCount],
               'message' => ''
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
    * Get Graph Activity Data of acoount
    */
    public function getAccountActivityData(Request $request){
      try {

         $result = ProfileRepository::getGraphActivityData($request);

         return response()->json(
            [
               'success' => true,
               'data' => $result,
               'message' => ''
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
