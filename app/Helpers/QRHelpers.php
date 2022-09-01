<?php 

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/* Get the dynamic guest profile url */
function getGuestDynamicUrl($profileId){
      
    $domainUriPrefix = Config::get('constants.domainUriPrefix');
    $androidPackageName = Config::get('constants.androidPackageName');
    $iosBundleId = Config::get('constants.iosBundleId');
    // $url = url("/");
    $url = url("/guest-profile/$profileId");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, Config::get('constants.firebaseGuestProfileCurlUrl'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    /* Curl data */
    curl_setopt($ch, CURLOPT_POSTFIELDS,'{
            "dynamicLinkInfo": {
                "domainUriPrefix" : "'.$domainUriPrefix.'" ,
                "link" : "'.$domainUriPrefix.'/profileId='.$profileId.'",
                "androidInfo": {
                    "androidPackageName": "'.$androidPackageName.'",
                    "androidFallbackLink": "'.$url.'",
                },
                "iosInfo": {
                    "iosBundleId": "'.$iosBundleId.'",
                    "iosFallbackLink": "'.$url.'",
                },
                "desktopInfo": {
                    "desktopFallbackLink": "'.route('guest-profile', [$profileId]).'"
                },
                "socialMetaTagInfo": {
                    "socialTitle": "Forevory",
                    "socialDescription": "Add Social",
                    "socialImageLink": "'.url('assets/images/logo.svg').'",
                },
            }
        }'
    );

    /* Receive server response */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    curl_close ($ch);

    /* Further processing */
    if(!empty($server_output)){
        return json_decode($server_output)->shortLink;
    }else{
        return '';
    }
    
}

/* Function used to generate qr for profile */
function QrCode($link, $profileId){
    $fileData = QrCode::size(Config::get('constants.qrCode.size'))
    ->margin(Config::get('constants.qrCode.margin'))
    ->color(Config::get('constants.qrCode.color1'), Config::get('constants.qrCode.color2'), Config::get('constants.qrCode.color3'))
    ->eye(Config::get('constants.qrCode.eye'))
    ->style(Config::get('constants.qrCode.style'))
    ->format(Config::get('constants.qrCode.format'))
    ->merge(url(Config::get('constants.qrCode.defaultImageurl')), .3, true)
    ->errorCorrection('H')
    ->generate($link.'?profileId='.$profileId);

    if(!empty($fileData)){

        $fileName = config('constants.profile_media').'/'.$profileId.'/'.getRandomName().'.'.'png';
        $saveQrImage = uploadBaseCodeMedia($fileName,$fileData);
        if($saveQrImage){
            $imagePath = '/var/www/html/public/qrcode/'.getRandomName().'.'.'png';
            QrCodeBackground($fileName,$imagePath);

            /* Move image from local to bucket */
            if(uploadBaseCodeMedia($fileName,file_get_contents($imagePath))) {
                unlink($imagePath);
            }
            return $fileName;
        }
    }
    return false;
}

/* Function used to add background in qr code */
function QrCodeBackground($fileName,$imagePath){
    if(!empty($fileName)) {
        $image1 = url(Config::get('constants.qrCode.backgroundImageurl'));
        $image2 = Storage::url($fileName);
        
        list($width,$height) = getimagesize($image2);

        $image1 = imagecreatefromstring(file_get_contents($image1));
        $image2 = imagecreatefromstring(file_get_contents($image2));

        imagecopymerge($image1,$image2,13,61,0,0,$width,$height,100);
        header('Content-Type:image/png');
       
        imagepng($image1,$imagePath);
        imagedestroy($image1);
        imagedestroy($image2);

    }
}
