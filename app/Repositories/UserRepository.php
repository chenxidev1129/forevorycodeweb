<?php

namespace App\Repositories;
use App\User;
use App\Repositories\UserDeviceRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\ProfileSubscriptionRepository;
use App\Services\StripeService;
use App\Models\PasswordReset;
use App\Jobs\SendEmailJob;
use App\Jobs\ProfileSubscriptionJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
//use Mixpanel;

class UserRepository{

    /**
     * Send forgot password email
     * @param Request $request
     * @return User $user
     */
    public static function sendForgotPasswordEmail($request)
    {

        try {

            $user = self::findWhereIn(['email' => $request->email],'user_type',config('constants.roles'));
            
            if(empty($user)){
                throw new Exception(__('message.password_reset_email_not_found'), 1);
            }

            $token =  getRandomId();
            
            $link = route('admin/showResetPasswordPage', ['verify_token' => $token]);
            
            $userEmail = [
                'name' =>  ucfirst($user->first_name).' '.ucfirst($user->last_name),
                'email' => $user->email,
            ];

            $emailData = [
                'link' => $link,
                'name' => ucfirst($user->first_name).' '.ucfirst($user->last_name),
                'email' => $user->email,
                'subjectLine' => __('message.subject_reset_password'),
                'template' => 'admin.email.forgot-password'
            ];
            
            $passwordReset = PasswordReset::create([
                'email' => $user->email,
                'token' => $token
            ]);
            
            if(empty($passwordReset)){

                throw new Exception(__('message.password_reset_link_error'), 1);
            }

            SendEmailJob::dispatch($userEmail, $emailData );

            return $user;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Find one
     * @param array $where
     * @param array $with
     * @return  User
     */
    public static function findOne($where, $with = [])
    {
        return User::with($with)->where($where)->first();
    }

    /**
     * Update
     * @param array $where
     * @param array $data
     * @return  boolean
     */
    public static function update($where=[], $data = [])
    {
        return  User::where($where)->update($data);
    }
    
    /**
     * Find one
     * @param array $whereIn
     * @param array $with
     * @return  User
     */
    public static function findWhereIn($where,$type, $whereIn, $with = [])
    {
        return User::with($with)->where($where)->whereIn($type,$whereIn)->first();
    }

    /**
     * Admin login 
     * @param LoginRequest $request
     * @return array
     */

    public static function adminLogin(LoginRequest $request){
        try {
        
            $user = self::findWhereIn(['email' => $request->email], 'user_type',  config('constants.roles'));

            if(empty($user)){
                throw new Exception( __('message.email_not_found'), 1);
            }

            if (!Hash::check($request->password, $user->password)){
                throw new Exception(__('message.invalid_password'), 1);
            } 

            if ($user->status == 'inactive'){
                throw new Exception(__('message.access_account_inactive'), 1);
            } 

            $check = Auth::guard('admin-web')->loginUsingId($user->id);
            
            if(empty($check)){
                throw new Exception( __('message.login_went_wrong'), 1);
            }
        
            return $user;

            }catch(\Exception $ex){
            throw $ex;
        }
    } 

    /**
     * Change password
     * @param $request
     * @throws Exception 
     */
    public static function changePassword($request){
        try{

            $userData = getUserDetail();
            if(!empty($userData)){
                $user  = self::findOne(['id' => $userData->id]);
                if(!empty($user)){

                    $post = $request->all();

                    if(!empty($post['new_password'])){

                        if (!Hash::check($post['current_password'], $user->password)){
                            throw new Exception(__('message.invalid_current_password'));
                        } 

                        if (Hash::check($post['new_password'], $user->password)){
                            throw new Exception(__('message.old_new_password_error'));
                        } 

                        $user->password = Hash::make($post['new_password']);

                        if (!$user->save()){
                            throw new Exception(__('message.password_update_error'));
                        }

                        return $user;    
                    }
                    throw new Exception(__('message.something_went_wrong'));
                }
                throw new Exception(__('message.user_not_found'));
            }
            throw new Exception(__('message.user_not_found'));
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

    /**
     * Function used to create access account. 
     * @param $request
     * @return boolean
     * @throws Exception
     */
    public static function addAccessAccount($request){
        try{

            $post = $request->all();
            $password = generateRandomPassword();
            $post['password'] = Hash::make($password);
            $createAccessAccout = User::Create($post);

            if(!empty($createAccessAccout)){
                /* Send access account password */
                SendEmailJob::dispatch(
                    [
                        'email' => $post['email']
                    ], 
                    [
                        'name' => ucfirst($post['first_name']),
                        'email' => $post['email'],
                        'password' => $password,
                        'type' => 'add',
                        'subjectLine' => __('message.subject_access_account'),
                        'template' => 'admin.email.access-account-password'
                    ]
                );

                return true;
            }
            throw new Exception(__('message.access_account_not_created'));
        } catch (\Exception $ex) {
            throw $ex;
        }         
    }    

    /**
    * Function used to update access account.
    * @param $request
    * @param type $id
    * @return boolean
    * @throws Exception
    */
    public static function updateAccessAccount($request,$id){
        try{
            $post = $request->all();
            $getAccount  = self::findOne(['id' => $id]);
            
            if(empty($getAccount)){
                throw new Exception(__('message.account_not_found'));
            }
                
            $password = generateRandomPassword();
            
            $updateAccessAccount = User::where('id',$id)->update(
                [
                    'first_name' => $post['first_name'],
                    'user_type' => $post['user_type'],
                    'email' => $post['email']
                ]
            );
            
            if(empty($updateAccessAccount)){
                throw new Exception(__('message.access_account_not_updated'));
            }
                
            if($getAccount->email !== $post['email']){
                /* Update Access account details */
                User::where('id',$id)->update(['password' =>Hash::make($password)]);
            
                $getupdatedAccount  = self::findOne(['id' => $id]);
                if(!empty($getupdatedAccount)){

                    /* Send access account password before update */
                    SendEmailJob::dispatch(
                        [
                            'email' => $getAccount->email
                        ], 
                        [
                            'name' => ucfirst($getAccount->first_name),
                            'email' => $getupdatedAccount->email,
                            'password' => $password,
                            'type' => 'update',
                            'subjectLine' => __('message.subject_access_account'),
                            'template' => 'admin.email.access-account-password'
                        ]
                    );
                    
                    /* Send updated access account password */
                    SendEmailJob::dispatch(
                        [
                            'email' => $getupdatedAccount->email
                        ], 
                        [
                            'name' => ucfirst($getupdatedAccount->first_name),
                            'email' => $getupdatedAccount->email,
                            'password' => $password,
                            'type' => 'update',
                            'subjectLine' => __('message.subject_access_account'),
                            'template' => 'admin.email.access-account-password'
                        ]
                    );
                }

            }
            return true;
            
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }

   /**
    * Function used to update access account status.
    * @param $request
    * @param $id
    * @return boolean
    * @throws Exception
    */
    public static function updateAccessAccountStatus($request,$id){
        try{
            $post = $request->all();
            $updateStatus = User::where('id',$id)->update(['status' => $post['status']]);
            
            if(empty($updateStatus)){
                throw new Exception(__('message.access_account_status_not_updated'));
            }
                
            if($post['status'] == 'active'){
                $getAccount  = self::findOne(['id' => $id]);
                /* Send updated access account password */
                SendEmailJob::dispatch(
                    [
                        'email' => $getAccount->email
                    ], 
                    [
                        'name' => ucfirst($getAccount->first_name),
                        'status' => $getAccount->status,
                        'subjectLine' => __('message.subject_access_account'),
                        'template' => 'admin.email.access-account-status-update'
                    ]
                );
            }

            return true;

        } catch (\Exception $ex) {
            throw $ex;
        }   
    }   

    /**
     * Function used to create app sign up. 
     * @param $request
     * @return boolean
     */
    public static function appSignUp($request){
        try{

            $post = $request->all();
            $otp = generateOtp();
            $post['password'] = Hash::make($post['password']);
            $post['otp_expires_at'] = otpExpiredAt();
            $post['otp'] = $otp;
            /* profile_status 1 for complete profile */
            $post['profile_status'] = '1';
            
            if($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filePath = getRandomName().'.'.'png';
                /* Upload profile image into storage */
               $profileImage =  uploadMedia($filePath, $file);
               $post['image'] = $filePath;
            }

            $createUserAccount = User::Create($post);
            if(!empty($createUserAccount)){
                
                $user = User::select('id','first_name', 'last_name', 'email', 'country_code', 'phone_number', 'image' ,'country', 'state' ,'city' ,'zip_code' ,'address', 'email_verified', 'status', 'created_at', 'profile_status', 'country_short_name')->where(['id' => $createUserAccount->id, 'user_type' => 'user'])->first();
              
                $user['authorization'] = '';
                $user['device_token'] = '';

                /* Send otp  via email */
                SendEmailJob::dispatch(
                    [
                        'email' => $createUserAccount->email
                    ], 
                    [
                        'name' => ucfirst($createUserAccount->first_name),
                        'otp' => $otp,
                        'subjectLine' => __('message.subject_sign_up_verification'),
                        'template' => 'user.email.sign-up-otp-verification'
                    ]
                );

                return $user;
            }
            throw new Exception(__('message.something_went_wrong'));
        } catch (\Exception $ex) {
            throw $ex;
        }         
    }

    /**
     * Function used to create use details.
     * @param $request
     * @return boolean
     */
    public static function signUpCreate($request){
        try{

            $post = $request->all();
             
            $otp = generateOtp();
            $post['password'] = Hash::make($post['password']);
            $post['phone_number'] = preg_replace('/[^0-9]/', '', $post['phone_number']); 
            $post['otp_expires_at'] = otpExpiredAt();
            $post['otp'] = $otp;
            /* profile_status 1 for complete profile */
            $post['profile_status'] = '1';
            
            if($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filePath = getRandomName().'.'.'png';
                /* Upload profile image into storage */
                $uploadStatus =  uploadMedia($filePath, $file);
                if(!empty($uploadStatus)){
                    $post['image'] = $filePath;
                }
            }

            $createUser = User::Create($post);
            if(!empty($createUser))
            {
                /* Send otp */
                SendEmailJob::dispatch(
                    [
                        'email' => $createUser->email
                    ],
                    [
                        'name' => ucfirst($createUser->first_name),
                        'otp' => $otp,
                        'subjectLine' => __('message.subject_sign_up_verification'),
                        'template' => 'user.email.sign-up-otp-verification'
                    ]
                );

                return $createUser->email;
            }
            throw new Exception(__('message.something_went_wrong'));
        } catch (\Exception $ex) {
            throw $ex;
        }         
    }

    /**
     * Function used for otp verification.
     * @param $request
     * @return User
     */
    public static function otpVerification($request)
    {
        try {
            $post = $request->all();
            $user = self::findOne(['email' => $post['email'], 'user_type' => 'user']);
            
            if(empty($user)){
                throw new Exception(__('message.user_not_found'));
            }
               
            $currentTime = Carbon::now()->setTimezone("UTC");
            if ($currentTime > $user->otp_expires_at) {
                throw new Exception('otp expired');
            }
            
            if($user->otp != $post['otp']){
                throw new Exception(__('message.invalid_otp'));
            }
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->email_verified = '1';
            $user->save();
            
            $check = Auth::guard('user-web')->loginUsingId($user->id);
            if (empty($check)){
                throw new Exception( __('message.login_went_wrong'));
            }
            return $user;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * User login 
     * @param  $request
     * @return array
     */
    public static function userLogin($request){
        try {
        
            $user = self::findOne(['email' => $request->email, 'user_type' => 'user']);
            
            if(empty($user)){
                throw new Exception( __('message.user_details_not_found'));
            }
           
            if($user->status != 'active'){
                throw new Exception( __('message.access_account_inactive'));
            }
            
            if (!Hash::check($request->password, $user->password)){
                throw new Exception(__('message.invalid_password'));
            } 

            if($user->email_verified == '0'){
                $otp = generateOtp();
    
                $user->otp = $otp;
                $user->otp_expires_at = otpExpiredAt();
                $user->save();

                /* Send otp verification email to user */
                SendEmailJob::dispatch(
                    [
                        'email' => $user->email
                    ], 
                    [
                        'name' => ucfirst($user->first_name),
                        'otp' => $otp,
                        'subjectLine' => __('message.subject_sign_up_verification'),
                        'template' => 'user.email.login-otp-verification'
                    ]
                );

                return $user;
            }
           
            $check = Auth::guard('user-web')->loginUsingId($user->id);
           
            if(!empty($check)){
                return $user;
            }

            throw new Exception( __('message.login_went_wrong'));

        }catch(\Exception $ex){
            throw $ex;
        }
    } 

    /**
     *  Funtion used to resend otp.
     * @param  $request
     * @throws Exception $ex
     * @return $user
     */
    public static function resendOtp($request){
        try {
            $post = $request->all();
            $otp = generateOtp();
            $user = self::findOne(['email' => $post['email'], 'user_type' => 'user']);
           
            if(empty($user)){
                throw new Exception( __('message.user_details_not_found'));
            }
           
            $updateStatus = User::where('id', $user->id)->update(['otp' => $otp,'otp_expires_at'=> otpExpiredAt()]);
            if(!empty($updateStatus)){
                
                if($post['email_type'] == 'sign-up'){
   
                    $UserEmail = [
                        'email' => $user->email
                    ];
    
                    $Emaildata = [
                        'name' => ucfirst($user->first_name),
                        'otp' => $otp,
                        'subjectLine' => __('message.subject_sign_up_verification'),
                        'template' => 'user.email.sign-up-otp-verification'
                    ];
    
                }
                if($post['email_type'] == 'forgot-password'){
   
                    $UserEmail = [
                        'email' => $user->email
                    ];
    
                    $Emaildata = [
                        'name' => ucfirst($user->first_name),
                        'otp' => $otp,
                        'subjectLine' => __('message.subject_forgot_password'),
                        'template' => 'user.email.reset-password-otp'
                    ];
    
                }
                 
                if($post['email_type'] == 'login'){
   
                    $UserEmail = [
                        'email' => $user->email
                    ];
    
                    $Emaildata = [
                        'name' => ucfirst($user->first_name),
                        'otp' => $otp,
                        'subjectLine' => __('message.subject_sign_up_verification'),
                        'template' => 'user.email.login-otp-verification'
                    ];
    
                }

                SendEmailJob::dispatch($UserEmail, $Emaildata);

                return true;
            }
            throw new Exception( __('message.something_went_wrong'));

        }catch(\Exception $ex){
            throw $ex;
        }
    }   
    
    /**
     * Funtion used to resend otp.
     * @param  $request
     * @return string
     */

    public static function sendForgotPasswordOtp($request){
        try {
            $post = $request->all();
            $otp = generateOtp();
            $user = self::findOne(['email' => $post['email'], 'user_type' => 'user']);

            if(empty($user)){
                throw new Exception(__('message.email_not_found'));
            }
        
            $updateOtp = User::where('id', $user->id)->update(['otp' => $otp, 'otp_expires_at'=> otpExpiredAt()]);
            
            if(!empty($updateOtp)){
                
                /* Send otp via mail */ 
                SendEmailJob::dispatch(
                    [
                        'email'=> $user->email
                    ], 
                    [
                        'name' => ucfirst($user->first_name),'otp' => $otp,
                        'subjectLine' => __('message.subject_forgot_password'),
                        'template' => 'user.email.reset-password-otp'
                    ]
                );

                return $user->email;
            }
            throw new Exception( __('message.otp_not_sent'));
            

        }catch(\Exception $ex){

            throw $ex;
        }
    } 
    
    /**
     * Function used for forgot password otp verification.
     * @param $request
     * @return User $user
     */
    public static function forgotPasswordOtpVerification($request)
    {
        try {
            $post = $request->all();
            $user = self::findOne(['email' => $post['email'], 'user_type' => 'user']);
            if (empty($user)){
                throw new Exception(__('message.email_not_found'));
            }
                
            $currentTime = Carbon::now()->setTimezone("UTC");
            if ($currentTime > $user->otp_expires_at) {
                throw new Exception('otp expired');
            }

            if ($user->otp == $post['otp']){
                $user->otp = null;
                $user->otp_expires_at = null;
                $user->save();
                return $user->email;
            }

            throw new Exception(__('message.invalid_verification_code'));
            
        } catch (\Exception $ex) {
        
            throw $ex;
        }
    } 
    
    /**
     * Function used for forgot password otp verification.
     * @param $request
     * @return User $user
     */
    public static function resetForgotPassword($request)
    {
        try {
            $post = $request->all();
            $user = self::findOne(['email' => $post['email'], 'user_type' => 'user']);
            if (empty($user)){
                throw new Exception(__('message.user_details_not_found'));
            }
                
            User::where('id', $user->id)->update([
                'password' =>  Hash::make($post['password']),
                'login_type' => 'forevory'
            ]);          
            
            return true;
            
        } catch (\Exception $ex) {
            throw $ex;
        }
    }     
    
    /**
     * Sign up with socials details. 
     * @param $request
     * @return boolean
     */
    public static function signUpSocial($request,$login_type){
        try{

            if($request['email']) {
                /* create user */
                $post['email'] = $request['email'];
                $post['user_type'] = 'user';
               
                $post['email_verified'] = '1';
                $post['login_type'] = $login_type;

                $firstName = '';
                $lastName = '';

                if($login_type == 'facebook') {

                    $post['facebook_id'] = $request['id'];

                    $firstName = $request['first_name'];
                    $lastName = $request['last_name'];

                } elseif($login_type == 'google') {

                    $post['google_id'] = $request['id'];

                    $firstName = $request['given_name'];
                    $lastName = $request['family_name'];

                } elseif($login_type == 'apple') {

                    $post['apple_id'] = $request['sub'];

                    //$firstName = $request['name'];
                }
                
                $user = User::updateOrCreate(['email'=>$request['email']],$post);
                
                if(!empty($user)){
                    
                    if(empty($user->first_name)){
                        self::update(['id'=>$user->id], ['first_name'=>$firstName]);
                    }

                    if(empty($user->last_name)){
                        self::update(['id'=>$user->id], ['last_name'=>$lastName]);
                    }

                    $check = Auth::guard('user-web')->loginUsingId($user->id);
                    if (empty($check)){
                        throw new Exception( __('message.login_went_wrong'));
                    }
                
                    return $user;
                }

                throw new Exception(__('message.something_went_wrong'));
            }

            throw new Exception(__('message.social_email_not_found'));
        } catch (\Exception $ex) {
            throw $ex;
        }         
    }

    /**
     * Function used to get user account information.
     * @param $request
     * @return User $user
     */
    public static function getUserProfile($request)
    {
        try {
            $userId = Auth::guard($request->guard)->user()->id;
            $user = self::findOne(['id' => $userId]);
            
            if (!empty($user)){
                return $user;
            }

            throw new Exception(__('message.user_not_found'));      
            
        } catch (\Exception $ex) {
        
            throw $ex;
        }
    }   
    
    /**
     * Function used to update user profile. 
     * @param $request
     * @return boolean
     * @throws Exception
     */
    public static function editAccountDetail($request){
        try{
            $post = $request->all();
            $userData = getUserDetail();
            $user = self::findOne(['id' => $userData->id]);
            if(!empty($user)){
                $data = [
                    'phone_number' => preg_replace('/[^0-9]/', '', $post['phone_number']),
                    'country_code' => $post['country_code'],
                    'country_iso_code' => $post['country_iso_code'],
                    'first_name' => $post['first_name'],
                    'last_name' => $post['last_name'],
                    'email' => $post['email'],
                    'address' => $post['address'],
                    'zip_code' => $post['zip_code'],
                    'country' => $post['country'],
                    'state' => $post['state'],
                    'city' => $post['city'],
                    'lat' => $post['lat'],
                    'lng' => $post['lng'],
                    'profile_status' => '1',
                    'country_short_name' => $post['country_short_name'],
                ];

                if($request->hasFile('profile_image')) {
    
                    $image = $request->file('profile_image');
                    $image_name = getRandomName().'.'.'png';
                    /* Upload new image into storage */
                    $uploadStatus = uploadMedia($image_name, $image); 
                    if(!empty($uploadStatus)){
                        $data['image'] = $image_name;
                        /* Remove old image from storage */
                        if(!empty(isExistsFile($user->image))){
                            deleteUploadMedia($user->image);
                        }
                    }
                }

                $updateStatus = User::where(['id' =>$userData->id])->update($data);
                if(!empty($updateStatus)){
                    return User::select('id','first_name', 'last_name', 'email', 'country_code', 'phone_number', 'image', 'country', 'state' ,'city' ,'zip_code' ,'address', 'email_verified', 'status', 'created_at' , 'profile_status', 'login_type','country_short_name')->where(['id' => $user->id, 'user_type' => 'user'])->first();
               
                }
               
                throw new Exception(__('message.something_went_wrong'));
            }

            throw new Exception(__('message.user_not_found'));

        } catch (\Exception $ex) {
            throw $ex;
        }         
    }  

    /**
     * Function used to schedule profile subscription to cancel using queue job.
     * @param $profile
     * @return boolean
     */
    public static function inactiveProfileSubscription($profile){
        try{

            $retrieveSubscription = StripeService::retrieveSubscription([
                'subscription_id'=> $profile->profileLatestSubscription->subscription_id
            ]);
           
            /* Check if profile subscription is active and not schedule to canceled */
            if(!empty($retrieveSubscription) && $retrieveSubscription['status'] != 'canceled'){
    
                $scheduleToCanceledSubscription = StripeService::updateSubscription([
                    'subscription_id'=> $profile->profileLatestSubscription->subscription_id
                ]);
    
                if(!empty($scheduleToCanceledSubscription) && !empty($scheduleToCanceledSubscription['cancel_at_period_end'])){
                      return true;
                }
            }
    
            return false;

        }catch(Exception $ex){ 
            throw $ex; 
        }
       
    }

    /**
     * Function used to resume profile subscription using queue job.
     * @param $profile
     * @return boolean
     */
    public static function resumeProfileSubscription($profile){
        try{

            $retrieveSubscription = StripeService::retrieveSubscription([
                'subscription_id'=> $profile->profileLatestSubscription->subscription_id
            ]);
           
            /* Check if profile subscription is active and not schedule to canceled */
            if(!empty($retrieveSubscription) && !empty($retrieveSubscription['cancel_at_period_end']) && $retrieveSubscription['status'] != 'canceled'){
                                        
                $resumeProfileSubscription = StripeService::updateCanceledSubscription(['subscription_id'=> $profile->profileLatestSubscription->subscription_id]);
                
                /*If subscription schedule to canceled  */
                if(!empty($resumeProfileSubscription) && empty($resumeProfileSubscription['cancel_at_period_end'])){
                    
                    return true;
                
                }
            }
     
            return false;

        }catch(Exception $ex){ 
            throw $ex;
        }

    }

    /**
     * Function used to update user account status.
     * @param $request
     * @param int $id
     * @return boolean
     */
    public static function updateUserAccountStatus($request,$id){
        DB::beginTransaction();
        try{
      
            $post = $request->all();
          
            if($post['status'] == 'inactive'){
           
                $profileActiveSubscription = ProfileRepository::getProfileWithActiveSubscription(['id'=> $id]);
               
                if(!empty($profileActiveSubscription) && count($profileActiveSubscription) > 0){
                    foreach($profileActiveSubscription as $profileRow){
                      
                        /* If profile have an active subscription */
                        if(!empty($profileRow->profileLatestSubscription)){
                           
                            if($profileRow->profileLatestSubscription->subscription->slug !== 'life_time'){
                                
                                /*If subscription schedule to canceled  */
                                if($profileRow->profileLatestSubscription->canceled_by != 'user'){
                                    
                                    ProfileSubscriptionJob::dispatch($profileRow, 'inactive');

                                    $updateProfileSubscription = ProfileSubscriptionRepository::updateProfileSubscription([
                                        'id' => $profileRow->profileLatestSubscription->id], ['stripe_status' => 'canceled', 'canceled_by' => 'admin']);
                                    
                                    if(empty($updateProfileSubscription)){
                                        DB::rollBack();
                                        return false;
                                    }    

                                }   
                                
                            }else{
                                
                                $updateProfileSubscription = ProfileSubscriptionRepository::updateProfileSubscription(['id' => $profileRow->profileLatestSubscription->id], ['canceled_by' => 'admin']);
                                
                                if(empty($updateProfileSubscription)){
                                    DB::rollBack();
                                    return false;
                                }   
                            }
                                        
                            $updateProfile = ProfileRepository::updateProfileStatus(['id'=> $profileRow->id, 'status'=>'inactive' ]);
                            if(empty($updateProfile)){
                                DB::rollBack();
                                return false;
                            } 
                        }
                    }
                  
                }
               
            }else{

                $profileInactiveSubscription = ProfileRepository::getProfileWithCanceledSubscription(['id'=> $id]);
              
                if(!empty( $profileInactiveSubscription) && count($profileInactiveSubscription) > 0){
                    foreach($profileInactiveSubscription as $profileRow){
                       
                        if(!empty($profileRow->profileLatestSubscription)){
        
                            if($profileRow->profileLatestSubscription->subscription->slug !== 'life_time'){
                                
                                if($profileRow->profileLatestSubscription->canceled_by == 'admin'){
                                  
                                    ProfileSubscriptionJob::dispatch($profileRow, 'active');

                                    $updateProfileSubscription = ProfileSubscriptionRepository::updateProfileSubscription(['id' => $profileRow->profileLatestSubscription->id],['stripe_status' => 'active']);
                                    if(empty($updateProfileSubscription)){
                                       
                                        DB::rollBack();
                                        return false;
                                    }
                                   
                                }

                            }

                            if($profileRow->status == 'inactive'){

                                $updateProfileStatus = ProfileRepository::updateProfileStatus(['id'=> $profileRow->id, 'status'=> 'active']);
                                if(empty($updateProfileStatus)){
                                       
                                    DB::rollBack();
                                    return false;
                                }

                            }

                        }

                    }

                }  

            }

            $updateUser = User::where('id',$id)->update(['status' => $post['status']]);
           
            if(!empty($updateUser)){
                DB::commit();
                return true;  
            }
            
            DB::rollBack();
            return false;
            
        } catch (\Exception $ex) {
            throw $ex;
        }   
    }   

    /**
     * Function used to update user account detail by admin. 
     * @param $request
     * @return boolean
     * @throws Exception
     */
    public static function editAccount($request){
        try{
            $update = [];
            $post = $request->all();
            $update['phone_number'] = preg_replace('/[^0-9]/', '', $post['phone_number']); 
            $update['country_code'] = $post['country_code'];
            $update['country_short_name'] = $post['country_short_name'];
            $update['country_iso_code'] = $post['country_iso_code'];
            $update['first_name'] = $post['first_name'];
            $update['last_name'] = $post['last_name'];
            $update['email'] = $post['email'];
            $update['address'] = $post['address'];
            $update['zip_code'] = $post['zip_code'];
            $update['country'] = $post['country'];
            $update['state'] = $post['state'];
            $update['city'] = $post['city'];
            $update['lat'] = $post['lat'];
            $update['lng'] = $post['lng'];
            
            User::where('id', $post['id'])->update($update);
            return true;

        } catch (\Exception $ex) {
            throw $ex;
        }         
    }

    /**
     * Function used get user profile. 
     * @param $request
     * @return boolean
     * @throws Exception
     */
    public static function getUserCreatedProfile($request){
        try{
            $post = $request->all();
            $getUserProfile = User::with(['profile' => function($query) { 
                $query->where('status','!=', 'deleted')->orderBy('id', 'desc');
            }])->where('id', $post['userId'])->first();
            return $getUserProfile;
        } catch (\Exception $ex) {
            throw $ex;
        }         
    }

    /**
     * Function used to get profile user with profile count. 
     * @param $id
     * @return getProfileUser
     * @throws Exception
     */
    public static function getProfileUser($id){
        try{
            $getProfileUser = User::withCount('profile')->where(['user_type'=> 'user','id' => $id])->first();
            return $getProfileUser;
        } catch (\Exception $ex) {
            throw $ex;
        }         
    }

    /**
     * Function used for api user login
     * @param  $request
     * @throws Exception $ex
     * @return array
     */
    public static function appLogin($request){
        try {
        
            $user = User::select('id','first_name', 'last_name', 'email', 'country_code', 'phone_number', 'image' ,'country', 'state' ,'city' ,'zip_code' ,'address', 'email_verified', 'password', 'status', 'created_at' , 'profile_status','login_type','country_short_name')->where(['email' => $request->email, 'user_type' => 'user'])->first();
         
            if(empty($user)){
                throw new Exception( __('message.email_not_found'));
            }

            if($user->status != 'active'){
                throw new Exception( __('message.access_account_inactive'));
            }

            if (!Hash::check($request->password, $user->password)){
                throw new Exception(__('message.app_invalid_password'));
            } 

            if($user->email_verified == '0'){
                $otp = generateOtp();
                
                User::where('id',$user->id)->update(['otp'=> $otp, 'otp_expires_at'=> otpExpiredAt()]);
                
                $UserEmail = [
                    'email' => $user->email
                ];

                $Emaildata = [
                    'name' => ucfirst($user->first_name),
                    'otp' => $otp,
                    'subjectLine' => __('message.subject_sign_up_verification'),
                    'template' => 'user.email.login-otp-verification'
                ];

                // Send otp.
                SendEmailJob::dispatch($UserEmail, $Emaildata);
                $user['authorization'] = '';
                $user['device_token'] = '';
                return $user;
            }
           
            $deviceInfo = self::setDeviceToken($request, $user);
            
            $user['authorization'] = $deviceInfo['token'];
            $user['device_token'] = $deviceInfo['device_token'];

            return $user;

            }catch(\Exception $ex){
            throw $ex;
        }
    } 

    /**
     * Set device token
     * @param Request $request
     * @throws Exception $ex
     */
    public static function setDeviceToken($request, $user)
    {
        
        DB::beginTransaction();
        try {
            $post = $request->all();
            /* Check token create */
            $token = JWTAuth::fromUser($user);
           
            $deviceInfo = [];
            $deviceInfo['user_id'] = $user['id'];
            $deviceInfo['device_token'] = $post['device_token'];
            $deviceInfo['device_type'] = $request->header('device_type');
            $deviceInfo['token'] = $token;
            $deviceInfo['status'] = 'active';

            if (UserDeviceRepository::updateOrCreate(['user_id' => $user['id']], $deviceInfo) ) {
                DB::commit();
               return $deviceInfo;
            } else {
                DB::rollback();
                throw new Exception(__('message.something_went_wrong'));
            }

        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    


    /**
     * Function used for otp verification.
     * @param $request
     * @return User
     */
    public static function appOtpVerification($request)
    {
        try {
            $post = $request->all();            
            $user = User::select('id','first_name', 'last_name', 'email', 'country_code', 'phone_number', 'image', 'otp', 'country', 'state' ,'city' ,'zip_code' ,'address', 'email_verified', 'status', 'created_at', 'profile_status','login_type', 'country_short_name','otp_expires_at')->where(['email' => $post['email'], 'user_type' => 'user'])->first();
           
            if (empty($user)){
                throw new Exception(__('message.user_not_found'));
            }
            
            $currentTime = Carbon::now()->setTimezone("UTC");
            if ($currentTime > $user->otp_expires_at) {
                throw new Exception('otp expired');
              
            }else{
               
                if ($user->otp != $post['otp']){
                    throw new Exception(__('message.invalid_otp'));
                }

                $user->email_verified = '1';
                $user->otp_expires_at = null;
                $user->otp = null;
                $user->save();
               
                $deviceInfo = self::setDeviceToken($request, $user);
    
                $user['authorization'] = $deviceInfo['token'];
                $user['device_token'] = $deviceInfo['device_token'];
                    
                return $user;

            }

        } catch (\Exception $ex) {
        
            throw $ex;
        }
    }

    /**
     * Sign up with socials. 
     * @param $request
     * @return $getUser
     * @throws Exception
     */
    public static function appSignUpSocial($request){
        DB::beginTransaction();
        try{
            $post = $request->all();
           
            $post['user_type'] = 'user';
            $post['email_verified'] = '1';
            $updateOrCreateUser = "";
            $socialId = '';
            /* Get user from token */
            $getSocialUser = Socialite::driver($post['login_type'])->userFromToken($post['auth_token']);
            
            if(empty($getSocialUser )){
                DB::rollback();
                throw new Exception(__('message.something_went_wrong'));
            }
            
            if($post['login_type'] == 'facebook') {
                
                $post['facebook_id'] = $getSocialUser->id;
                $socialId = 'facebook_id';                   
            
            } elseif($post['login_type'] == 'google') {
                
                $post['google_id'] = $getSocialUser->id;
                $socialId = 'google_id';      

            } elseif($post['login_type'] == 'apple') {

                $post['apple_id'] = $getSocialUser->id;
                $socialId = 'apple_id';     
                
            }else{
                DB::rollback();
                throw new Exception(__('message.something_went_wrong'));
            }
            
            if(!empty($getSocialUser->email)){
                $post['email'] = $getSocialUser->email;
                $getUser = self::findOne(['email' => $getSocialUser->email]);
                
                if(!empty($getUser)){
                    $updateOrCreateUser = User::updateOrCreate(['email'=> $getUser->email],$post);
                }else{
                    $updateOrCreateUser = User::updateOrCreate([$socialId => $getSocialUser->id],$post);
                }
                  
            }else{
                $updateOrCreateUser = User::updateOrCreate([$socialId => $getSocialUser->id],$post);
                
            }  
            
            if(!empty($updateOrCreateUser))
            {
                $getUser = User::select('id','first_name', 'last_name', 'email', 'country_code', 'phone_number', 'image', 'country', 'state' ,'city' ,'zip_code' ,'address', 'email_verified', 'status', 'created_at' , 'profile_status', 'login_type', 'country_short_name')->where(['id' => $updateOrCreateUser->id, 'user_type' => 'user'])->first();
   
                $deviceInfo = self::setDeviceToken($request, $getUser);

                $getUser['authorization'] = $deviceInfo['token'];
                $getUser['device_token'] = $deviceInfo['device_token'];
                DB::commit();
                return $getUser;
            }

            DB::rollback();
            throw new Exception(__('message.something_went_wrong'));

        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }         
    }

    /**
     * Function used to logout user 
     * @param $request
     * @return 
     * @throws Exception
     */
    public static function logout($request)
    {
        try {
            $userData = getUserDetail();
            
            if (!empty($userData)) {
                $authorization = $request->header('authorization');
                $token = str_replace('Bearer ', '', $authorization);
                JWTAuth::invalidate($token);
                return true;
            }
            
            throw new Exception(__('message.something_went_wrong'));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Function used to get account count. 
     * @return 
     * @throws Exception
     */
    public static function getAccountCount()
    {
        try {
            return User::where('user_type','user')->count();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
