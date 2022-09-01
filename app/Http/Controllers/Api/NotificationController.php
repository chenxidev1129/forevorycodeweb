<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{

    /**
     * NotificationController controller instance.
     * @param NotificationService $notificationService
     * @return void
     */
    public function __construct() {

    }
    
    /**
     * Get notitification list
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getNotifications(Request $request)
    {
        try {

            $notitifications = NotificationRepository::getALLNotifications($request);
            return response()->json(
                [
                    'success' => true,
                    'data' => $notitifications,
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

    /**
     * Get notitification list
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function dismissNotification(Request $request,$notificationId)
    {
        try {
            $request->request->add(['notificationId' => $notificationId]);
            $isDelete = NotificationRepository::deleteNotification($request);

            if(!empty($isDelete)) {

                $getNotification = NotificationRepository::getALLNotifications($request);

                return response()->json(
                    [
                        'success' => true,
                        'data' => $getNotification,
                        'message' => __('message.success')
                    ],
                    Response::HTTP_OK
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'data' => [],
                        'message' => __('Invalid Notification')
                    ],
                    Response::HTTP_OK
                );
            }
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