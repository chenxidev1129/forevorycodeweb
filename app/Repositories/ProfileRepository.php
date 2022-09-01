<?php

namespace App\Repositories;
use App\Models\Profile;
use App\Models\ProfileSubscription;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SubscriptionPlanRepository;
use App\Repositories\ProfileMediaRepository;
use App\Repositories\ProfileStoriesArticleRepository;
use App\Repositories\ProfileGraveSiteRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class ProfileRepository {

    /**
     * Find one
     * @param array $where
     * @param array $with
     * @return  Profile
     */
    public static function findOne($where, $with = [])
    {
        return Profile::with($with)->where($where)->first();
    }

    /**
     * Find one
     * @param array $whereIn
     * @param array $with
     * @return  Profile
     */
    public static function findWhereIn($where,$type, $whereIn, $with = [])
    {
        return Profile::with($with)->where($where)->whereIn($type,$whereIn)->first();
    }

    /**
     * List of all user profile
     * @return  Profile
     */
    public static function getAllProfiles()
    {
        $userId = getUserDetail()->id;

        return Profile::select('profiles.id','profiles.user_id','profiles.profile_name','profiles.gender','profiles.date_of_birth','profiles.date_of_death','profiles.short_description','profiles.profile_image','profiles.banner_image','profiles.journey','profiles.qrcode_image','profiles.shared_link','profiles.created_at','profiles.status','profile_subscriptions.id as subscription_id','profiles.purchase_type')
        
        ->leftJoin('profile_subscriptions', function($join) {
            $join->on('profile_subscriptions.profile_id', '=', 'profiles.id')->whereRaw('profile_subscriptions.id IN (select MAX(subs.id) from profile_subscriptions as subs join profiles as profile on subs.profile_id = profile.id group by subs.profile_id)');

        })->with('profileMediaAudio.user','ProfileGraveSite')->with(['profileMediaAudio' => function($query) use($userId){
            $query->whereNotIn('user_id', [$userId])->latest()->first();
        }])
        
        ->where(['profiles.user_id' => $userId])->where(function ($query) {
            $query->Where('profiles.status', '=', 'active')
                ->orWhere('profiles.status', '=', 'expired');

        })->orderBy('profiles.id', 'desc')->orderBy('profile_subscriptions.profile_id', 'desc')->get();
    }

    /**
     * Function used to create profile. 
     * @param $request
     * @return boolean
     * @throws Exception
     */
    public static function createProfile($request, $subscription){
        try{
            $isExistProfile = ProfileSubscription::where('subscription_id',$subscription->id)->where('customer_id',$subscription->customer)->first();

            if(!empty($isExistProfile)) {
                return $isExistProfile->profile_id;
            }

            $user_id = Auth::guard($request->guard)->user()->id;

            $data['user_id'] = $user_id;
            $profile = Profile::Create($data);

            if(!empty($profile))
            {
                $stripe_price_id = $subscription->plan->id;

                $free_trial = SubscriptionPlanRepository::findOne(['slug'=>'free_trial']);
                $purchase_plan = SubscriptionPlanRepository::findOne(['stripe_price_id'=>$stripe_price_id]);

                $subscriptionData = [
                    'profile_id' => $profile->id,
                    'customer_id' => $subscription->customer,
                    'subscription_id' => $subscription->id,
                    'purchase_plan_id' => $purchase_plan->id,
                    'plan_id' => $free_trial->id,
                    'subscription_price' => $purchase_plan->price,
                    'free_trial_days' => $free_trial->days,
                    'purchase_plan_days' => $purchase_plan->days,
                    'start_date' => date('Y-m-d H:i:s',$subscription->current_period_start),
                    'end_date' => date('Y-m-d H:i:s',$subscription->current_period_end),
                ];

                /* Create profile Subscription */
                ProfileSubscription::Create($subscriptionData);

                return $profile->id;
            }
            throw new Exception(__('message.something_went_wrong'));
        } catch (\Exception $ex) {
            throw $ex;
        }         
    }

    /**
     * Function used to upload profile image. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function uploadProfileImage($request){
        try{
            $post = $request->all();
            $getProfileImage = self::findOne(['id'=>$post['profile_id']]);
            
            if($request->hasFile('profile_image')) {
                $imageFile = $request->file('profile_image');
                $profileImage = config('constants.profile_media').'/'.$post['profile_id'].'/'.getRandomName().'.'.'png';
                $uploadStatus = uploadMedia($profileImage, $imageFile);

                if($uploadStatus){
                    $memberDetail = ['profile_image'=>getUploadMedia($profileImage)];
                    $finalTree = self::updateFamilyTreeDetail(json_decode($getProfileImage->family_tree,true),$memberDetail);

                    Profile::where('id', $post['profile_id'])->update(['profile_image'=>$profileImage,'family_tree'=>json_encode($finalTree)]);
                    /* Remove old profile image */
                    if (!empty($getProfileImage->profile_image) && strpos($getProfileImage->profile_image, config('constants.profile_media')) !== false) {
                        deleteUploadMedia($getProfileImage->profile_image);
                    }

                    return true;
                }
                
            }

            throw new Exception(__('message.profile_image_not_found'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to upload profile banner image. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function uploadProfileBannerImage($request){
        try{
            $post = $request->all();
            $getProfileBannerImage = self::findOne(['id'=>$post['profile_id']]);
            
            if($request->hasFile('banner_image')) {

                $imageFile = $request->file('banner_image');
                $profileBannerImage = config('constants.profile_media').'/'.$post['profile_id'].'/'.getRandomName().'.'.'png';
                $uploadStatus = uploadMedia($profileBannerImage, $imageFile);//  uploadBaseCodeMedia($profileBannerImage, $image);
                
                if($uploadStatus){
                    Profile::where('id', $post['profile_id'])->update(['banner_image' => $profileBannerImage]);
                    /* Remove old banner image */
                    if (!empty($getProfileBannerImage->banner_image) && strpos($getProfileBannerImage->banner_image, config('constants.profile_media')) !== false) {

                        deleteUploadMedia($getProfileBannerImage->banner_image);
                    }
                    return true;
                }

            }

            throw new Exception(__('message.profile_banner_image_not_found'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to update profile detail. 
     * @param $request
     * @throws Exception
     * @return boolean
     */
    public static function editProfile($request){ 
        try{
            
            $post = $request->all();
           
            /* Update image caption and position. */
            if(!empty($post['image_position'])){
                ProfileMediaRepository::updateProfileImageCaption($request);
            }

            /* Update video caption and position. */
            if(!empty($post['video_position'])){
                ProfileMediaRepository::updateProfileVideoCaption($request);
            }

            /* Update article image and position with title and text. */
            if(!empty($post['articles-image-position'])){
                ProfileStoriesArticleRepository::updateProfileArticle($request);
            }

            /*  Update audio caption. */
            if(!empty($post['audio_caption_id'])){
                ProfileMediaRepository::updateVoiceNoteCaption($request);
            }
            /* Create or Update grave site image and location. */
            ProfileGraveSiteRepository::addGraveSiteDetails($request);
           
            /* Update profile detail */
            self::updateProfileDetail($request);
           
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to update profile detail. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function updateProfileDetail($request){
        try{
            $post = $request->all();
            
            $updateProfileDetail = array(
                'profile_name' => $post['profile_name'],
                'date_of_birth' => date("Y-m-d", strtotime($post['birth_date'])),
                'date_of_death' => date("Y-m-d", strtotime($post['death_date'])),
                'short_description' =>  $post['short_description'],
                'journey' => $post['journey'],
                'gender' => $post['gender'],
                'is_saved' => '1',
            );

            $updateProfile = Profile::where('id', $post['profile_id'])->update($updateProfileDetail);
            if(!empty($updateProfile)) {

                $profileData = Profile::where('id', $post['profile_id'])->first();

                /* Update Family tree */
                $memberData = array(
                    'profile_name' => $post['profile_name'],
                    'date_of_birth' => date("d/m/Y", strtotime($post['birth_date'])),
                    'date_of_death' => date("d/m/Y", strtotime($post['death_date'])),
                    'gender' => $post['gender']
                );

                $familyTree = self::updateFamilyTreeDetail(json_decode($profileData->family_tree,true),$memberData);
                Profile::where('id', $post['profile_id'])->update(['family_tree'=>json_encode($familyTree)]);

                return true;
            }
  
            throw new Exception(__('message.something_went_wrong'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to generate QR code. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function generateProfileQrCode($request){
        try{
            $profile_id = $request->profile_id;
            /* Check for exist qr code */
            $profile = self::findOne(['id'=>$profile_id]);
         
            if(!empty($profile->qrcode_image)) {
                $exists = isExistsFile($profile->qrcode_image);

                if ($exists) {
                    return $profile;
                }

            }

            /* Generate profile deep linking url */
            $getSharedLink = getGuestDynamicUrl($profile->id);
           
            if(empty($getSharedLink)){
                throw new Exception(__('message.something_went_wrong'));
            }
    
            /* Generate QR code */
            $qrCodeFile = QrCode($getSharedLink, $profile_id);
            // $fileName = config('constants.profile_media').'/'.$profile_id.'/'.getRandomName().'.'.'png';
            // $saveQrImage = uploadBaseCodeMedia($fileName,$qrCodeFile);
            
            if($qrCodeFile){
                $profile->qrcode_image = $qrCodeFile;
                $profile->shared_link = $getSharedLink.'?profileId='.$profile_id;
                $profile->save();
                return $profile;
            }

            return false;
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to get profile media photos. 
     * @param $request
     * @return $getProfileMediaPhotos
     * @throws Exception
     */   
    public static function getProfileMediaPhotos($request){
        try{
           $post = $request->all();
           $getProfileMediaPhotos = self::findOne(['id' => $post['profile_id']] , ['profileMediaImage']);
           return $getProfileMediaPhotos;

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to get profile media photos. 
     * @param $request
     * @return $getGraveSiteDetail
     * @throws Exception
     */   
    public static function getProfile($request){
        try{
           $post = $request->all();
           $getProfile = self::findOne(['id' => $post['profile_id']]);
           return $getProfile;

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to get profile media video. 
     * @param $request
     * @return $getProfileMediaVideo
     * @throws Exception
     */   
    public static function getProfileMediaVideo($request){
        try{
           $post = $request->all();
           $getProfileMediaVideo = self::findOne(['id' => $post['profile_id']] , ['profileMediaVideo']);
           return $getProfileMediaVideo;

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }


    /**
     * Function used to get profile media video. 
     * @param $request
     * @return $getProfileMediaVideo
     * @throws Exception
     */   
    public static function getProfileWithMedia($request){
        try{
           $post = $request->all();
           $getProfileMediaVideo = Profile::whereHas(['profileMediaAudio' => function($query) {
                $query->where('type','audio')->where('status','active')->orderBy('position','asc');
            }])->where(['id'=>$post['profile_id']])
            ->get();
           return $getProfileMediaVideo;

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }   

    /**
     * Function used to edit profile detail. 
     * @param  $request
     * @return Boolean
     * @throws Exception
     */   
    public static function editProfileDetail($request){
        try{

            $post = $request->all();
            /* Check and update family tree data */
            $profile = Profile::where('id', $request['profileId'])->first();
            $profile->is_saved = '1';
            $profile->profile_name = $post['profile_name'];
            $profile->date_of_birth = $post['date_of_birth'];
            $profile->date_of_death = $post['date_of_death'];
            $profile->short_description = $post['short_description'];
           
            if (!empty($post['gender'])) {
                $profile->gender = $post['gender']; // male, female, other
            }
            
            if (!empty($profile->save())) {
                return ['qrcode_image'=> $profile->qrcode_image, 'shared_link'=> $profile->shared_link];;
            }

            throw new Exception(__('message.something_went_wrong'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }
    
    /**
     * Function used to get profile detail. 
     * @param  $request
     * @return $profileDetail
     * @throws Exception
     */   
    public static function getProfileDetail($request){
        try{
            $request->user_id =  getUserDetail()->id;

            if(!empty($request->profileId)){

                $profileDetail = Profile::with('profileMediaImage','profileMediaVideo', 'profileMediaAudio.user', 'ProfileStoriesArticle', 'ProfileGraveSite')->with(['ProfileSubscription' => function($query) {
                    $query->select('id','profile_id','status','stripe_status')->latest()
                    ->first();
                  
                }])->where(['id' => $request['profileId']])->first();
                
                if(!empty($profileDetail)){
                  
                    $profile_guest_book = ProfileMediaRepository::getProfileGuestLatestBook($request);

                    $profileDetail['profile_guest_book'] = !empty($profile_guest_book) ? $profile_guest_book : array() ;

                    $profileDetail->default_grave_image = 'headstone.jpg';
                }

                return $profileDetail;
            }
            throw new Exception(__('message.profile_not_found'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }
    
    /**
     * Function used to upload profile image using form data. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function uploadProfileCoverImage($request){
        try{
            $post = $request->all();
            $getProfile = Profile::where(['id' => $post['profileId']])->first();
            
            if (empty($getProfile)) {
                throw new Exception(__('message.profile_not_found'));
            }
           
            /* Check if profile image is in request */
            if ($request->hasFile('profile_image')) {
                
                $file = $request->file('profile_image');
                $profileMedia = config('constants.profile_media').'/'.$post['profileId'].'/'.getRandomName().'.'.'png';
                /* Upload profile image into storage */
                $uploadProfileImageStatus = uploadMedia($profileMedia, $file);
                
                if (empty($uploadProfileImageStatus)) {
                    throw new Exception(__('message.something_went_wrong_while_uploading_profile_image'));
                }
            
                $memberDetail = ['profile_image'=> getUploadMedia($profileMedia)];
                $finalTree = self::updateFamilyTreeDetail(json_decode($getProfile->family_tree,true), $memberDetail);
                $updateProfileImageStatus = Profile::where('id', $post['profileId'])->update(['profile_image' => $profileMedia, 'family_tree'=>json_encode($finalTree)]);

                if ($updateProfileImageStatus) {
                  
                    /* Remove old image from storage */
                    if (!empty($getProfile->profile_image) && strpos($getProfile->profile_image, config('constants.profile_media')) !== false) {
                        if (!empty(isExistsFile($getProfile->profile_image))) {
                            deleteUploadMedia($getProfile->profile_image);
                        }
                    }

                } else {
                    throw new Exception(__('message.something_went_wrong'));
                }

            }

            /* Check if banner image in in request */
            if ($request->hasFile('banner_image')) {
                
                $file = $request->file('banner_image');
                $bannerImage = config('constants.profile_media').'/'.$post['profileId'].'/'.getRandomName().'.'.'png';
                /* Upload profile image into storage */
                $uploadBannerImageStatus = uploadMedia($bannerImage, $file);
                
                if (empty($uploadBannerImageStatus)) {
                    throw new Exception(__('message.something_went_wrong_while_uploading_backgroud_image'));
                }
                
                $updateBannerImageStatus = Profile::where('id', $post['profileId'])->update(['banner_image' => $bannerImage]);
                if ($updateBannerImageStatus) {

                    /* Remove old image from storage */
                    if (!empty($getProfile->banner_image) && strpos($getProfile->banner_image, config('constants.profile_media')) !== false) {

                        if (!empty(isExistsFile($getProfile->banner_image))) {
                            deleteUploadMedia($getProfile->banner_image);
                        }
                    }
                } else {
                    throw new Exception(__('message.something_went_wrong'));
                }
                
            }

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to edit profile journey. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function editProfileJourney($request){
        try{
            $post = $request->all();
            $result = Profile::where('id', $request['profileId'])->update(['journey' => $post['journey'] ]);
            if (!empty($result)) {
                return true;
            }
            throw new Exception(__('message.something_went_wrong'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Get active visitor with profile count
     * @return profileCount
     */
    public static function activeVisitorProfileCount($request="", $startDate='', $endDate='')
    {   
        if(empty($startDate) && empty($endDate)){
            $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');
        }

        $profiles = Profile::where('status','active');
    
        $profiles = $profiles->whereHas('ProfileSubscription', function($q) use($request, $startDate, $endDate){
            if(!empty($request['subscriptionPlan']) && $request['subscriptionPlan'] != 'all'){
                $q->where('purchase_plan_id', $request['subscriptionPlan']);
            }
            $q->where('status', 'active')->whereBetween(DB::raw('DATE(created_at)'),[$startDate,$endDate]);
        });
        
        $profiles = $profiles->get();
    
        $visitors = ProfileMediaRepository::getActiveVisitor();
        
        $profileList = $userList = [];

        /* get active profiles count */
        if(!empty($profiles) && count($profiles)) {
            foreach($profiles as $profile) {
                if(!empty($profile->user_id)) {
                    $profileList[] = $profile->user_id;
                }
            }
        }
        /* get active visitors count */
        if(!empty($visitors)) {
            foreach($visitors as $visitor) {
                if(in_array($visitor->user_id, $profileList)) {
                    $userList[] = $visitor->user_id;
                }
            }
        }
       
        return count($userList);
    }

    /**
     * Update profile status. 
     * @param $request
     * @return boolean
     */   
    public static function updateProfileStatus($request){
        try{
            
            $profileUpdateResult = Profile::where('id', $request['id'])->update(['status' => $request['status'] ]);
            if(!empty($profileUpdateResult)){
                return true;
            }
            throw new Exception(__('message.something_went_wrong'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Get profile with active subscription
     * @param $request
     * @return boolean
     */   
    public static function getProfileWithActiveSubscription($request){
        try{
            
            $getProfile = Profile::select('id','status')->whereHas('profileLatestSubscription', function($query){ 
                $query->where('status', '=', 'active');
                $query->orWhere(function($query){
                    $query->where('stripe_status', '=', 'active');
                    $query->where('stripe_status', '=', 'canceled');
                });
            })->with('profileLatestSubscription.subscription:id,slug')->where('user_id', $request['id'])->orderBy('id', 'DESC')->get();
            if(!empty($getProfile)){
                return $getProfile;
            }
            return false;
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Get profile with active subscription
     * @param $request
     * @return boolean
     */   
    public static function getProfileWithCanceledSubscription($request){
        try{
            
            $getProfile = Profile::select('id','user_id','status')->whereHas('profileLatestSubscription', function($query){ 
                $query->where('status', '=', 'active');
                $query->orWhere(function($query){
                    $query->where('stripe_status', '=', 'active');
                    $query->where('stripe_status', '=', 'canceled');
                })
                ->whereIn('canceled_by', ['admin','user']);
            })->with('profileLatestSubscription.subscription:id,slug')->orderBy('id','DESC')->where(['user_id'=> $request['id'], 'status'=>'inactive'])->get();
            
            if(!empty($getProfile)){
                return $getProfile;
            }
            return false;
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }


    /**
     * Get Graph Activity Data of an account
     * @return profileCount
     */
    public static function getGraphActivityData($request)
    {   
        $range = $request->limit;

        /* Start for every month */
        if($range == 'year') {
            /* Start get data of every month */
            $now = Carbon::now();
            $currentYear = $now->year;
            $currentMonth = $now->month;
            
            for($i=1;$i<=12;$i++) {

                if($i <= 9) {
                    $month = '0'.$i;
                } else {
                    $month = $i;
                }

                $start_date = $currentYear.'-'.$month.'-01';
                $end_date = $currentYear.'-'.$month.'-31';
                $monthName = Carbon::parse($start_date)->format('F');

                $subscriptions = 0;

                if($month <= $currentMonth) {
                    /* Get subscriptions data */
                    $getProfiles = Profile::withCount(['ProfileSubscription' => function($query) use($start_date,$end_date){ 
                            $query->whereBetween('created_at',[$start_date,$end_date]);
                        }])
                        ->where('user_id', $request['id'])->get();

                    if(!empty($getProfiles) && count($getProfiles)>0) {
                        foreach ($getProfiles as $getProfile) {
                            $subscriptions = $subscriptions + $getProfile->profile_subscription_count;
                        }
                    }
                }

                $data['labels'][] = $monthName;
                $data['subscriptions'][] = $subscriptions;

            }
        }
        /* Start for last 30 Day */
        elseif($range == 'month') {
            /* Start get data of every day */
            $now = Carbon::now();
            $currentYear = $now->year;
            $currentMonth = $now->month;
            $currentDay = $now->day;
            $days = $now->daysInMonth;
             
            for($i=1;$i<=$days;$i++) {
                if($i <= 9) {
                    $day = '0'.$i;
                } else {
                    $day = $i;
                }

                $subscriptions = 0;

                if($day <= $currentDay) {
                    /* Get subscriptions data */
                    $date = $currentYear.'-'.$currentMonth.'-'.$day;
                    $getProfiles = Profile::withCount(['ProfileSubscription' => function($query) use($date){ 
                        $query->whereDate('created_at',$date);
                    }])
                    ->where('user_id', $request['id'])->get();

                    if(!empty($getProfiles) && count($getProfiles)>0) {
                        foreach ($getProfiles as $getProfile) {
                            $subscriptions = $subscriptions + $getProfile->profile_subscription_count;
                        }
                    }
                } 

                $data['labels'][] = (int) $day;
                $data['subscriptions'][] = $subscriptions;
            }
        }
        /* Start for last 7 Day */
        elseif($range == 'week') {
            /* Start get data of every day */
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->format('Y-m-d');
            $date = $startDate;
            $dayName = $now->startOfWeek()->format('l');

            $endDate = $now->endOfWeek()->format('Y-m-d');
            $currentDate = $now->format('Y-m-d');

            for($i=1;$i<=7;$i++) {

                $subscriptions = 0;

                if($date <= $currentDate) {
                    /* Get subscriptions data */
                    $getProfiles = Profile::withCount(['ProfileSubscription' => function($query) use($date){ 
                        $query->whereDate('created_at',$date);
                    }])
                    ->where('user_id', $request['id'])->get();

                    if(!empty($getProfiles) && count($getProfiles)>0) {
                        foreach ($getProfiles as $getProfile) {
                            $subscriptions = $subscriptions + $getProfile->profile_subscription_count;
                        }
                    }
                }

                $data['labels'][] = $dayName;
                $data['subscriptions'][] = $subscriptions;

                $nextDate = Carbon::parse($date)->addDay();
                $date = $nextDate->format('Y-m-d');
                $dayName = $nextDate->format('l');
            }
        }
        return $data;
    }

    /**
     * Function used to save Family Tree. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function saveFamilyTree($request){
        try{
            $post = $request->all();
            /* check and update family tree data */
            $profile = Profile::where('id', $post['profile_id'])->first();
            if(!empty($profile)) {
                
                $updateProfileDetail['family_tree'] = $post['tree'];

                $result = Profile::where('id', $post['profile_id'])->update($updateProfileDetail);
                if(!empty($result)){

                    return self::updateLovedOneDetail(json_decode($post['tree'],true),$post['profile_id']);

                    return $result;
                }
            }
            throw new Exception(__('message.something_went_wrong'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to upload profile image. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function uploadProfileMemberImage($request){
        try{
            $post = $request->all();
            if(!empty($post['member_image'])) {
                $image = $post['member_image'];

                list($type, $image) = explode(';', $image);
                list(, $image) = explode(',', $image);
                $image = base64_decode($image);
                $image_name = config('constants.profile_media').'/'.$post['profile_id'].'/'.getRandomName().'.'.'png';
    
                $result =  uploadBaseCodeMedia($image_name, $image);
                if($result){
                    return url(getUploadMedia($image_name));
                }
            }
            throw new Exception(__('message.profile_image_not_found'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to get guest profile detail. 
     * @param  $request
     * @return $profileDetail
     * @throws Exception
     */   
    public static function getGuestProfile($request){
        try{
            $getprofile = self::findOne(['id' => $request->profileId]);
            if(!empty($getprofile) && !empty($request->profileId)){

                return Profile::with('profileMediaImage','profileMediaVideo', 'profileMediaAudio.user', 'ProfileStoriesArticle', 'ProfileGraveSite')->with(['profileMediaAudio' => function($query) use($getprofile){
                    $query->whereNotIn('user_id', [$getprofile->user_id])->orderBy('id', 'DESC');
                  
                }])->where(['id' => $request['profileId']])->first();
            }
            throw new Exception(__('message.profile_not_found'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }    

    /**
     * Function used to get guest profile with profile user detail. 
     * @param  $profileId
     * @return array
     */   
    public static function getGuestProfileWithUser($profileId){
        try{
           return ProfileRepository::findOne(['id' => $profileId], ['ProfileGraveSite']);
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to update family tree on profile update. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function updateFamilyTreeDetail($familyTree,$memberData) {
        try{
            $finalTree = [];
            if(!empty($familyTree)){

                foreach ($familyTree as $key => $value) {
                    if (strpos($key, 'li') !== false) {
                        $finalTree[$key] = self::updateFamilyTreeDetail($familyTree[$key],$memberData);
                    }

                    if (strpos($key, 'a') !== false) {
                        
                        if($familyTree[$key]['relation'] == 'self') {

                            if(!empty($memberData['profile_image'])) {
                                $familyTree[$key]['pic'] = $memberData['profile_image'];
                            }

                            if(!empty($memberData['profile_name'])) {
                                $familyTree[$key]['name'] = $memberData['profile_name'];
                            }

                            if(!empty($memberData['gender'])) {
                                $familyTree[$key]['gender'] = $memberData['gender'];
                            }

                            if(!empty($memberData['date_of_birth'])) {
                                $familyTree[$key]['dobDate'] = $memberData['date_of_birth'];
                            }

                            if(!empty($memberData['date_of_death'])) {
                                $familyTree[$key]['dodDate'] = $memberData['date_of_death'];
                            }

                        }

                        $finalTree[$key] = $familyTree[$key];
                    }

                    if($key == 'ul') {
                        $finalTree[$key] = self::updateFamilyTreeDetail($familyTree[$key],$memberData);
                    }
                }
                
            }
            return $finalTree;

            throw new Exception(__('message.something_went_wrong'));
            
        } catch (\Exception $ex) {
            throw $ex;
        } 
    }

    /**
     * Get profile with active subscription
     * @param $request
     * @return boolean
     */   
    public static function getProfileWithActiveSubscriptionByUserId($request){
        try{
            
            $getProfile = Profile::select('id','status')->whereHas('ProfileSubscription', function($query){ 
                $query->where(function ($query) {
                    $query->Where('status', '=', 'active')
                          ->orWhere('status', '=', 'expired');
                });
            })->where('user_id', $request['user_id'])->orderBy('id', 'DESC')->get();
            if(!empty($getProfile)){
                return $getProfile;
            }
            return false;
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to Loved One Detail on family tree update. 
     * @param $request
     * @return boolean
     * @throws Exception
     */   
    public static function updateLovedOneDetail($familyTree,$profile_id) {
        try{
            
            if(!empty($familyTree)){

                foreach ($familyTree as $key => $value) {
                    if (strpos($key, 'li') !== false) {
                        self::updateLovedOneDetail($familyTree[$key],$profile_id);
                    }
                    
                    if (strpos($key, 'a') !== false) {
                        
                        if($familyTree[$key]['relation'] == 'self') {
                            $memberData = [];

                            if(!empty($familyTree[$key]['pic'])) {
                                $profile_image = substr($familyTree[$key]['pic'], strrpos($familyTree[$key]['pic'], 'profile_media' ));
                                
                                // $storageUrl = Storage::url('');
                                // $profile_image = str_replace($storageUrl,'',$familyTree[$key]['pic']);

                                if($profile_image == $familyTree[$key]['pic']) {
                                    $memberData['profile_image'] = '';
                                } else {
                                    $memberData['profile_image'] = $profile_image;
                                }
                            }

                            if(!empty($familyTree[$key]['name'])) {
                                $memberData['profile_name'] = $familyTree[$key]['name'];
                            }

                            if(!empty($familyTree[$key]['gender'])) {
                                $memberData['gender'] = $familyTree[$key]['gender'];
                            }

                            if(!empty($familyTree[$key]['dobDate'])) {
                                $memberData['date_of_birth'] = date("Y-m-d", strtotime($familyTree[$key]['dobDate']));
                            }

                            if(!empty($familyTree[$key]['dodDate'])) {
                                $memberData['date_of_death'] = date("Y-m-d", strtotime($familyTree[$key]['dodDate']));
                            }

                            Profile::where('id',$profile_id)->update($memberData);
                        }
                    }

                    if($key == 'ul') {
                        self::updateLovedOneDetail($familyTree[$key],$profile_id);
                    }
                }
                
            }
            return $familyTree;

            throw new Exception(__('message.something_went_wrong'));
            
        } catch (\Exception $ex) {
            throw $ex;
        } 
    }


}
