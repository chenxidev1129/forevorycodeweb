<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

class NotificationController extends Controller
{

   /**
    * Load add or edit product form.
    * @return \Illuminate\Http\Response
    */
   public function index(Request $request)
   {
      $getNotification = NotificationRepository::getALLNotifications($request);
      $html = View::make('user.notification.loadNotificationWindow', compact('getNotification'))->render();
      
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
         $html = View::make('user.notification.loadNotificationWindow', compact('getNotification'))->render();

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
