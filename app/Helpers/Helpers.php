<?php 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ProdigiOrderHistory;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Models\UserDeviceToken;
use App\Models\Notification;
use App\Models\Profile;
use App\User;
use App\Models\ProfileSubscription;

/**
 * Function used to generate random token
 * @param type $length
 * @return string
 */
function getRandomId($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Function used to generate name
 * @param type $length
 * @return string
 */
function getRandomName() {
    return time().random_int(100, 999);
}

/**
 * Function used to generate random string.
 * @param type $length
 * @return string
 */
function generateRandomPassword($length=6){

    $digits    = array_flip(range('0', '9'));
    $lowercase = array_flip(range('a', 'z'));
    $uppercase = array_flip(range('A', 'Z')); 
    $special   = array_flip(str_split('@$%&*?'));
    $combined  = array_merge($digits, $lowercase, $uppercase, $special);

    $password  = str_shuffle(array_rand($digits) .
                            array_rand($lowercase) .
                            array_rand($uppercase) . 
                            array_rand($special) . 
                            implode(array_rand($combined, rand(4, 8))));

                            return $password;
}

/**
 * Function used to generate otp
 * @return number  
 */
function generateOtp()
{
    return random_int(100000, 999999);
}

/**
 * Get login user detail.
 * @return $user
 */
function getUserDetail()
{
    if (!empty(JWTAuth::getToken())) {
    
        return JWTAuth::parseToken()->authenticate();
        
    } else if (Auth::guard(request()->guard)->check()) {
      
        return Auth::guard(request()->guard)->user();
     
    }
    return false;
}

/**
 * Convert date into d/m/y format
 * @param $date $format
 * @return date
 */
function getConvertedDate($date , $format='')
{
    if(!empty($format)){
        if($format == 1){
            /* Return m/d/y format  */ 
            return date("m/d/Y", strtotime($date));
        }
        if($format == 2){
            /* Long month with day and year */   
            return  date('F d, Y', strtotime($date));
        }
    }
    return date("d/m/Y", strtotime($date));
}


/**
 * Upload media file to storage
 * @param $filePath , $file
 * @return boolean
 */
function uploadMedia($filePath, $file, $disk="") {
    $disk = $disk ?? config('filesystem.default');
    Storage::disk($disk)->put($filePath, file_get_contents($file), 'public');     
    return true;
}

/**
 * Upload base code media file to storage
 * @param $filePath , $file
 * @return boolean
 */
function uploadBaseCodeMedia($filePath, $file) {
   
    Storage::put($filePath, $file, 'public');     
    return true;
}


/**
 * Delete media file from storage
 * @param $fileName
 * @return boolean
 */
function deleteUploadMedia($fileName) {
    $exists = isExistsFile($fileName);
    if ($exists) {
        Storage::delete($fileName);
        return true;
    } else {
        return false;
    }   
}

/**
 * Get file from storage
 * @param $filePath , $file
 * @return string
 */
function getUploadMedia($fileName) {
    $exists = isExistsFile($fileName);
    if ($exists) {
        return Storage::url($fileName);
    } else {
        return false;
    } 
}

/**
 * Check media file existance
 * @param $fileName
 * @return boolean
 */
function isExistsFile($fileName) {
    $exists = Storage::exists($fileName);
    if (!empty($exists)) {
        return true;
    } else {
        return false;
    }   
}

/**
 * Get Reference Id for Send to Prodigi
 * @param $fileName
 * @return string
 */
function getReferenceId() {
    $getUniqueId = getRandomId();
    $isExist = ProdigiOrderHistory::where('reference_id',$getUniqueId)->first();
    if(!empty($isExist)) {
        getReferenceId();
    }
    return $getUniqueId;
}

function getRequestTimezone()
{
    $timeZone = 'UTC';
    $headerTimeZone = request()->header('timezone');  
    /* First check timezone in cookies */
    if (isset($_COOKIE['time_zone']) && !empty($_COOKIE['time_zone'])) {
        $timeZone = $_COOKIE['time_zone'];
    }
    /* Second check cookies in headers */
    if (!empty($headerTimeZone)) {
        $timeZone = $headerTimeZone;
    }
    return $timeZone;
}


function convertTimezone($date, $from, $to = "UTC") {
    $timezone = ($from) ? $from : date_default_timezone_get();
    $dateToConvert = ($date) ? $date : date('Y-m-d H:i:s');
    $convertDate = new \DateTime($dateToConvert, new \DateTimeZone($timezone));
    $convertDate->setTimeZone(new \DateTimeZone($to));
    return $convertDate;
}

function getCurrentDateTime($format = '') {
    if(!$format){
        $format = 'Y-m-d H:i:s';
    }
    return Carbon::now()->format($format);
}

function getNumberFormat($number) {
    return number_format($number);
}

function SubtractDaysDateTime($date, $no){
    return Carbon::parse($date)->subDays($no);
}

function SubtractMonth($date, $no){
    return Carbon::parse($date)->subMonths($no);
}

function getDBFormatDate($date) {
    $format = 'Y-m-d';
    if($date){
        return Carbon::parse($date)->format($format);
    }
    return Carbon::now()->format($format);
}

/**
 * Function used to get otp expired time 
 * @return datetime
 */
function otpExpiredAt() {
    return  Carbon::now()->addMinutes(config("constants.OTP.MAX_TIME"))->setTimezone("UTC")->format('Y-m-d H:i:s');
}

function sendPushNotification($data)
{
    if (!empty($data['user_id'])) {
        
        /* Save notification  */
        $notifaction['user_id'] = $data['user_id'];
        $notifaction['profile_id'] = $data['profile_id'];
        $notifaction['title'] = $data['title'];
        $notifaction['message'] = $data['message'];
        $notifaction['type'] = $data['type'];

        $result = Notification::create($notifaction);
        if (!empty($result)) {
            

            $customerId = $data['user_id'];
            $userDevice = UserDeviceToken::where('user_id', $customerId)->first();

            if (!empty($userDevice->device_token)) {
       
                $badgeCount = Notification::where(['user_id' => $customerId, 'is_read' => 0])->count();

                $deviceId = [$userDevice->device_token];
                $API_SERVER_KEY = config("constants.API_SERVER_KEY");
                if (!isset($API_SERVER_KEY)) {
                    return false;
                }
                $url = 'https://fcm.googleapis.com/fcm/send';

                if ($userDevice->device_type == 'ios') {
                    $fields = array(
                        'registration_ids' => $deviceId,
                        'notification' => array(
                            'body' => $data['message'],
                            //'title' => $data['title'],
                            'sound' => 'default',
                            
                           // 'message' => $data['message'],
                            'title' => $data['title']
                           // 'type' => $data['type'],
                           // 'badge_count' => $badgeCount,
                           // 'profile_id' => ($data['profile_id']) ? $data['profile_id'] : '',
                           
                        ),

                        'data' => array(
                            'message' => $data['message'],
                            'title' => $data['title'],
                            'type' => $data['type'],
                            'badge_count' => $badgeCount,
                            'profile_id' => ($data['profile_id']) ? $data['profile_id'] : ''
                        ),
                        'priority' => 'high'
                    );
                } else {
                    $fields = array(
                        'registration_ids' => $deviceId,
                        'notification' => array(
                            'body' => $data['message'],
                            // 'type' => $data['type'],
                            // 'badge_count' => $badgeCount,
                            'title' => $data['title'],
                            'sound' => 'default'
                            //'profile_id' => ($data['profile_id']) ? $data['profile_id'] : '',
                           
                        ),
                        'data' => array(
                                'message' => $data['message'],
                                'title' => $data['title'],
                                'type' => $data['type'],
                                'badge_count' => $badgeCount,
                                'profile_id' => ($data['profile_id']) ? $data['profile_id'] : ''
                            ),
                        'priority' => 'high'
                    );
                }

                $headers = array(
                    'Authorization: key=' . $API_SERVER_KEY,
                    'Content-Type: application/json'
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

                $result = curl_exec($ch);
                $result = json_decode($result, TRUE);
                	return $result;
                if ($result === FALSE) {
                    return false;
                }

                curl_close($ch);

            }
        }
    }
    return true;
}

/**
 * Function used to get user by profile 
 * @return datetime
 */
function getProfileUserId($profileId) {
    $profile = Profile::where('id',$profileId)->select('user_id')->first();
    if(!empty($profile)) {
        return $profile->user_id;
    }
    return false;
}


/**
 * Function used to get admin detail 
 * @return datetime
 */
function getAdmin() {
    return User::where('user_type','admin')->where('status','active')->first();
}

/**
 * downlaod qrcode file from storage
 * @param $filePath , $file
 * @return boolean
 */
function downlaodQrcode($profileId) {
    $qrcode_image = Profile::where('id',$profileId)->value('qrcode_image');
    if(!empty($qrcode_image)) {
        return Storage::download($qrcode_image);
    }
}


// iTunes Validator V2 Start Function
function receipt_Result_ITC($receipt_data) {

    if (config("constants.iosInAppPurchaseMode") == 'sandbox') {
        $url = config("constants.iosVerifyReceiptSandboxUrl");
    }
    else {
        $url = config("constants.iosVerifyReceiptLiveUrl");
    }

    $ch = curl_init($url);

    $data_string = '{"password": "'.config("constants.iosPassword").'", "receipt-data" : "'.$receipt_data.'" }';

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json;',
        'Content-Length: ' . strlen($data_string))
    );
    $output   = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if (200 == $httpCode) {
        $decoded = json_decode($output);
        return $decoded;
    }
    
    return false;
}
// iTunes Validator V2 End Function


// validate iOS subscription
function isSubscripted($subscriptionData) {
    if(!empty($subscriptionData)) {
        return ProfileSubscription::where('subscription_id',$subscriptionData->get('originalTransactionId'))->orderBy('id','desc')->first();
    }
    return false;
}
