<?php

namespace App\Repositories;
use App\Models\Notification;
use Exception;

class NotificationRepository {

    /**
     * Get user notification.
     * @param Request $request
     * @return Array
     */
    public static function getNotifications($request)
    {
        try{
            $userData = getUserDetail();
            return Notification::where('user_id', $userData->id)->where('status','active')->orderBy('id','desc')->get();
        } catch (Exception $ex) {
            throw $ex;
        }  
    }

    /**
     * Get all active notification.
     * @param Request $request
     * @return Array
     */
    public static function getALLNotifications($request)
    {
        try{
            if (!empty($request->limit)) {
                $limit = $request->limit;
            } else {
                $limit = 3;
            }

            $userData = getUserDetail();
            if(!empty($userData)) {
                return Notification::where('user_id', $userData->id)->where('status','active')->orderBy('id','desc')->paginate($limit);
            }
            return Notification::where('status','active')->orderBy('id','desc')->paginate($limit);
            
        } catch (Exception $ex) {
            throw $ex;
        }  
    }

    /**
     * Get all active notification.
     * @param Request $request
     * @return Array
     */
    public static function getALLPaginationNotifications($request)
    {
        try{
            return Notification::where('status','active')->orderBy('id','desc')->paginate(10);
           
        } catch (Exception $ex) {
            throw $ex;
        }  
    }
    
    /**
     * Delete notification.
     * @param Request $request
     * @return Array
     */
    public static function deleteNotification($request)
    {
        try{
            return Notification::where('id',$request->notificationId)->delete();
        } catch (Exception $ex) {
            throw $ex;
        }  
    }
    

}
