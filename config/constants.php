<?php

return [
    'HttpStatus' => [
        'OK' => 200,
        'CREATED' => 201,
        'BAD_REQUEST' => 400,
        'UNAUTHORIZED' => 401,
        'VALIDATION_EXCEPTION' => 422,
        'FORBIDDEN' => 403
    ],

    'Regex' =>[
        'EMAIL' => '/^([a-zA-Z0-9_\-\.\+]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/i',
        'PASSWORD' => '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])([a-zA-Z0-9@$!%*?&]{6,})$/',
        'NAME' => '/^[\a-zA-Z\s]*$/',
        'CHARACTERONLY' => '/^[\a-zA-Z\s]*$/', // Character only    
        'ALPHANUMERIC' => '/^[\wa-zA-Z0-9\s]*$/', //Alphnumeric
        'ADDRESS' =>'/^[\w.,-\s]+$/', // Alphanumeric with comma and , 

    ],

    'Prodigi' => [
        'REQUEST_URL' => env('PRODIGI_REQUEST_URL'),
        'REQUEST_TOKEN' => env('PRODIGI_REQUEST_TOKEN')
    ],

    "OTP" => [
        "MAX_TIME" => 10, // time in minutes
    ],

    'roles' =>[
        'admin','support','administrator'
    ],

    'profile_media' => env('PROFILE_MEDIA', 'profile_media'),
    'storage_type' => env('STORAGE_TYPE', 'public'),

    'DefaultValues' =>[
        'PAGINATION_RECORD'=> 10,
        'MEDIA_PAGINATION_RECORD' => 5,
        'GUEST_BOOK_PAGINATION_RECORD' => 5,
    ],
       
    'firebaseGuestProfileCurlUrl' => env('FIREBASE_GUEST_PROFILE_URL'),
    'domainUriPrefix' => env('DOMAIN_URL_PREFIX'),
    'androidPackageName' => env('ANDROID_PACKAGE_NAME'),
    'iosBundleId' => env('IOS_BUNDLED_ID'),
    'iosPassword' => env('IOS_PASSWORD'),
    'iosVerifyReceiptSandboxUrl' => env('IOS_VERIFYRECEIPT_SANDBOX_URL'),
    'iosVerifyReceiptLiveUrl' => env('IOS_VERIFYRECEIPT_LIVE_URL'),
    'iosInAppPurchaseMode' => env('IOS_IN_APP_PURCHASE_MODE'),
   
    'addressApiKey' => env('GOOGLE_ADDRESS_KEY'),

    'termConditionsUrl'=> env('TERMS_CONDITIONS_URL', 'https://www.forevory.com/'),
   
    /* Default path testing environment */
    'applePrivateKeyFilePath'=> env('APPLE_PRIVATE_KEY_FILE_PATH'),

    'qrCode' => [
        'size' => 260,
        'margin' => 2,
        'color1' => 58,
        'color2' => 186,
        'color3' => 221,
        'eye' => 'circle',
        'style' => 'square',
        'format' => 'png',
        'defaultImageurl' => 'assets/images/qr-logo.png',
        'backgroundImageurl' => 'assets/images/qrcode_background.png',
    ],    

    'API_SERVER_KEY' => env('API_SERVER_KEY'),

    'ffmpeg' => env('FFMPEGBINARY'),
    'ffprobe' => env('FFPROBEBINARY'),
    
    'aws_access_key_id' => env('AWS_ACCESS_KEY_ID'),
    'aws_secret_access_key' => env('AWS_SECRET_ACCESS_KEY'),

    'APP_ENV' => env('APP_ENV')
];
