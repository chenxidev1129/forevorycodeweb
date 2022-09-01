<?php

namespace App\Services;
use Carbon\Carbon;
use App\Jobs\SendEmailJob;
use App\Jobs\SendPushNotificationJob;
use App\Models\Profile;
use App\Models\ProfileSubscription;
use App\User;
use Exception;

/**
 * Class AppleToken
 * 
 * @package App\login\AppleToken
 */
class CroneService
{
    /**
     * Function used to send profile birth and death email to profile user using cron job.
     * @return boolean
     */   
    public static function sendProfileBirthEmail() {
        try {
            $currentMonth = Carbon::now()->format('m');
            $currentDay = Carbon::now()->format('d');

            $getActiveProfile = Profile::select('id','user_id','profile_name','date_of_birth','date_of_death')->with('user:id,first_name,last_name,email')->where(['status'=> 'active'])->get();
            
            if(empty($getActiveProfile)){
                return false;
            }

            /* Get admin user ID */
            $adminUser = getAdmin();

            foreach($getActiveProfile as $row){

                /* Check for DOB */
                if(!empty($row->date_of_birth) && ($currentMonth == Carbon::parse($row->date_of_birth)->format('m')) && $currentDay == Carbon::parse($row->date_of_birth)->format('d') ){

                    if(!empty($row->user->email)){
                        /* Send profile birth email */
                        SendEmailJob::dispatch(
                            [
                                'email' => $row->user->email
                            ], 
                            [
                                'profile_user' => ucwords($row->user->first_name.' '.$row->user->last_name),
                                'profile_name' => $row->profile_name,
                                'subjectLine' => "Today is ".ucwords($row->profile_name)."'s birthday.",
                                'template' => 'user.email.profile.profile-birthday'
                            ]
                        );

                        /* Send profile birth notification to user */
                        $age = Carbon::parse($row->date_of_birth)->diff(Carbon::now())->y;
                        if($age > 1) {
                            $age = $age." years";
                        } else {
                            $age = $age." year";
                        }
                        SendPushNotificationJob::dispatch(
                            [
                                'user_id' => $row->user_id,
                                'profile_id' => $row->id,
                                'title' =>  ucwords($row->profile_name)."'s Birth Day.",
                                'message' => "Today ".ucwords($row->profile_name)."'s would be ".$age." old",
                                'type' => 'birthday'
                            ]
                        );

                        /* Send profile birth notification to admin */
                        SendPushNotificationJob::dispatch(
                            [
                                'user_id' => $adminUser->id,
                                'profile_id' => $row->id,
                                'title' =>  "Today is ".ucwords($row->profile_name)."'s birthday.",
                                'message' => "May the angels sing ".ucwords($row->profile_name)."'s the most joyous chores of Happy Birthday today",
                                'type' => 'birthday'
                            ]
                        );
                    }
                }

                if(!empty($row->date_of_death) && ($currentMonth == Carbon::parse($row->date_of_death)->format('m')) && $currentDay == Carbon::parse($row->date_of_death)->format('d') ){

                    if(!empty($row->user->email)){
                        /* Send profile death email */
                        SendEmailJob::dispatch(
                            [
                                'email' => $row->user->email
                            ], 
                            [
                                'profile_user' => ucwords($row->user->first_name.' '.$row->user->last_name),
                                'profile_name' => $row->profile_name,
                                'subjectLine' => "Today is ".ucwords($row->profile_name)."'s death anniversary.",
                                'template' => 'user.email.profile.profile-death-anniversary'
                            ]
                        );

                        /* Send profile death notification to user */
                        $age = Carbon::parse($row->date_of_death)->diff(Carbon::now())->y;
                        if($age > 1) {
                            $age = $age." years";
                        } else {
                            $age = $age." year";
                        }
                        SendPushNotificationJob::dispatch(
                            [
                                'user_id' => $row->user_id,
                                'profile_id' => $row->id,
                                'title' =>  ucwords($row->profile_name)."'s Death Day.",
                                'message' => "Remembering ".ucwords($row->profile_name)."'s ".$age." since they passed",
                                'type' => 'deathday'
                            ]
                        );

                        /* Send profile death notification to admin */
                        SendPushNotificationJob::dispatch(
                            [
                                'user_id' => $adminUser->id,
                                'profile_id' => $row->id,
                                'title' => "Today is ".ucwords($row->profile_name)."'s death anniversary.",
                                'message' => "As each day passes we miss the loved one more and more. May ".ucwords($row->profile_name)."'s rest in peace.",
                                'type' => 'deathday'
                            ]
                        );
                    }
                }

            }
            return true;
            
        } catch (Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to send auto renew email notification to admin and user
     * @return boolean
     */
    public static function reminderForAutoRenewSubscription() {
        try {
            $date = Carbon::now()->addDays(3)->format("Y-m-d");
           
            $getProfileSubscription =  ProfileSubscription::select('id','profile_id','plan_id','end_date','status','stripe_status')->with('profile:id,user_id,profile_name','profile.user:id,first_name,last_name,email','subscriptionPlan:id,plan')->whereHas('subscriptionPlan', function($query){
                $query->whereNotIn('slug', ['free_trial']);
            })->where(['status'=> 'active','stripe_status'=> 'active'])->whereDate('end_date',$date)->get();

            if(empty($getProfileSubscription)){
                return false;
            }

            /* Get admin user ID */
            $adminUser = getAdmin();

            foreach($getProfileSubscription as $row){
                
                if(!empty($row->end_date)){
                    if(!empty($row->profile->user->email)){
                        
                        /* Send subscription renew reminder Email */
                        SendEmailJob::dispatch(
                            [
                                'email' => $row->profile->user->email
                            ], 
                            [
                                'profile_user' => ucwords($row->profile->user->first_name.' '.$row->profile->user->last_name),
                                'profile_name' => $row->profile->profile_name,
                                'renewal_plan' => $row->subscriptionPlan->plan,
                                'renew_date' => getConvertedDate($row->end_date, 1),
                                'subjectLine' => "Reminder for autorenewal",
                                'template' => 'user.email.profile.profile-subscription-renew-reminder'
                            ]
                        );

                    }

                    /* Send subscription renew reminder Notification to user */
                    SendPushNotificationJob::dispatch(
                        [
                            'user_id' => $row->profile->user_id,
                            'profile_id' => $row->profile->id,
                            'title' => "Profile Expiration",
                            'message' => "It looks like ".$row->profile->profile_name."'s memory page is going to expire soon. Be sure to renew their profile",
                            'type' => 'renewal'
                        ]
                    );

                    /* Send subscription renew reminder Notification to admin */
                    SendPushNotificationJob::dispatch(
                        [
                            'user_id' => $adminUser->id,
                            'profile_id' => $row->profile->id,
                            'title' => "Reminder for autorenewal",
                            'message' => "We wanted to remind you that, you will be billed from your saved card details for the ". $row->subscriptionPlan->plan ." amount of your plan upon expiration of your contract on ". getConvertedDate($row->end_date, 1).". Profile name - ".$row->profile->profile_name,
                            'type' => 'renewal'
                        ]
                    );
                    
                }
            }  
            return true;
        
        } catch(Exception $ex) {
            throw $ex; 
        }
    }  

    /**
     * Function used to send auto renew email notification to admin and user
     * @return boolean
     */
    public static function reminderForAutoRenewSubscriptionCustomer() {
        try {
            $date = Carbon::now()->addDays(7)->format("Y-m-d");
            
            $getProfileSubscription =  ProfileSubscription::select('id','profile_id','plan_id','end_date','status','stripe_status')->with('profile:id,user_id,profile_name','profile.user:id,first_name,last_name,email','subscriptionPlan:id,plan')->whereHas('subscriptionPlan', function($query){
                $query->whereNotIn('slug', ['free_trial']);
            })->where(['status'=> 'active','stripe_status'=> 'active'])->whereDate('end_date',$date)->get();
            \Log::info($date);
            if(empty($getProfileSubscription)){
                return false;
            }

            foreach($getProfileSubscription as $row) {
                
                /* Send subscription renew reminder Notification to user */
                SendPushNotificationJob::dispatch(
                    [
                        'user_id' => $row->profile->user_id,
                        'profile_id' => $row->profile->id,
                        'title' => "Profile Expiration",
                        'message' => "It looks like ".$row->profile->profile_name."'s memory page is going to expire soon. Be sure to renew their profile",
                        'type' => 'renewal'
                    ]
                );
                
            }  
            return true;
        
        } catch(Exception $ex) {
            throw $ex; 
        }
    }

    /**
     * Profile greeting after a day of profile creation.
     * @return boolean
     */
    public static function profileGreatingAfterDay() {
        try {
               
            $createdAt = Carbon::now()->subDays(1)->setTimezone("UTC")->format('Y-m-d');
            $currentDatetime = Carbon::now()->subDays(1)->setTimezone("UTC")->format('Y-m-d H:i');
            $getProfile = Profile::select('id','user_id','status','created_at')->where('status', 'active')->whereDate('created_at', '=', $createdAt)->get();
            
            foreach($getProfile as $row){
                
                $profileDate = date('Y-m-d H:i', strtotime($row->created_at));
                
                if ($currentDatetime == $profileDate) {
                    /* Send subscription renew reminder Notification */
                    SendPushNotificationJob::dispatch(
                        [
                            'user_id' => $row->user_id,
                            'profile_id' => $row->id,
                            'title' => "We love your profile",
                            'message' => "We love your profile. Keep it up!",
                            'type' => 'day'
                        ]
                    ); 
                }

            }

            return true;

        } catch(Exception $ex) {
            throw $ex; 
        }
    }  

    /**
     * Profile greeting after 30 days of profile creation.
     * @return boolean
     */
    public static function profileGreatingAfterMonth() {
        try {
               
            $createdAt = Carbon::now()->subDays(30)->setTimezone("UTC")->format('Y-m-d');
            $currentDateTime = Carbon::now()->subDays(30)->setTimezone("UTC")->format('Y-m-d H:i');
            $getProfile = Profile::select('id','user_id','status','created_at')->where('status', 'active')->whereDate('created_at', '=', $createdAt)->get();
            
            foreach($getProfile as $row){
                
                $profileDate = date('Y-m-d H:i', strtotime($row->created_at));
                
                if ($currentDateTime == $profileDate) {
                    /* Send subscription renew reminder Notification */
                    SendPushNotificationJob::dispatch(
                        [
                            'user_id' => $row->user_id,
                            'profile_id' => $row->id,
                            'title' => "Have a great day",
                            'message' => "Make every moment of every day memorable. I hope you have a great day today.",
                            'type' => 'month'
                        ]
                    ); 
                }

            }

            return true;

        } catch(Exception $ex) {
            throw $ex; 
        }
    }     

    /**
     * Function used to send week blog notification to new user
     * @return boolean
     */
    public static function weekBlogFirst() {
        try {
            $date = Carbon::now()->subDay(14)->format("Y-m-d");
            $toDay = Carbon::now()->format("Y-m-d");
           
            $getUsers = User::select('id')->where('status','active')->whereDate('created_at',$date)->get();

            if(empty($getUsers)){
                return false;
            }

            foreach($getUsers as $row) {
                
                /* Send subscription renew reminder Notification to user */
                SendPushNotificationJob::dispatch(
                    [
                        'user_id' => $row->id,
                        'profile_id' => 0,
                        'title' => "This weeks blog",
                        'message' => "Catch up on the latest with Forevory.",
                        'type' => 'blog'
                    ]
                );

                User::where('id',$row->id)->update(['blog_notification_date'=>$toDay]);
                
            }  
            return true;
        
        } catch(Exception $ex) {
            throw $ex; 
        }
    }

    /**
     * Function used to send week blog notification to user on every two week
     * @return boolean
     */
    public static function weekBlogEveryWeek() {
        try {
            $date = Carbon::now()->subDay(14)->format("Y-m-d");
            
            $getUsers = User::select('id')->where('status','active')->whereDate('blog_notification_date',$date)->get();

            if(empty($getUsers)){
                return false;
            }

            foreach($getUsers as $row) {
                
                /* Send subscription renew reminder Notification to user */
                SendPushNotificationJob::dispatch(
                    [
                        'user_id' => $row->id,
                        'profile_id' => 0,
                        'title' => "This weeks blog",
                        'message' => "Catch up on the latest with Forevory.",
                        'type' => 'blog'
                    ]
                );
                
            }  
            return true;
        
        } catch(Exception $ex) {
            throw $ex; 
        }
    }

}
