<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

class NotificationController extends Controller
{
   /**
    * Notification view
    */
   public function index(Request $request){
      $getNotification = NotificationRepository::getALLNotifications($request);
      return view('admin.notification.index', compact('getNotification'));
   }

   /**
    * Load add or edit product form.
    * @return \Illuminate\Http\Response
    */
   public function loadNotification(Request $request)
   {
      $getNotification = NotificationRepository::getALLNotifications($request);
      $html = View::make('admin.notification.loadNotificationWindow', compact('getNotification'))->render();
      
      $total = 0;
      $loadMore = 1;
      if(!empty($getNotification)) {
         $total = $getNotification->total();
         if($getNotification->lastPage() == $getNotification->currentPage()) {
            $loadMore = 0;
         }
      }

      return response()->json(
         [
               'success' => true,
               'html' => $html,
               'loadMore' => $loadMore,
               'total' => $total
         ],
         Response::HTTP_OK
      );
   }

   /**
    * Notification list.
    */
    public function notificationList(Request $request){
      $getNotification = NotificationRepository::getALLPaginationNotifications($request);
      return view('admin.notification.notificationList', compact('getNotification'));
   }

   /**
    * delete Notification.
    * @return \Illuminate\Http\Response
    */
   public function deleteNotification(Request $request)
   {
      NotificationRepository::deleteNotification($request);

      $total = 0;
      $loadMore = 1;
      $html = '';

      if (!empty($request->limit)) {
         $getNotification = NotificationRepository::getALLNotifications($request);
         $html = View::make('admin.notification.loadNotificationWindow', compact('getNotification'))->render();

         if(!empty($getNotification)) {
            $total = $getNotification->total();
            if($getNotification->lastPage() == $getNotification->currentPage()) {
               $loadMore = 0;
            }
         }
      }

      return response()->json(
         [
               'success' => true,
               'html' => $html,
               'loadMore' => $loadMore,
               'total' => $total
         ],
         Response::HTTP_OK
      );
   }


}
