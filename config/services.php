<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [    
        'client_id' => env('FACEBOOK_CLIENT_ID'),  
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),  
        'redirect' => env('FACEBOOK_REDIRECT_URI') 
    ],

    'google' => [    
        'client_id' => env('GOOGLE_CLIENT_ID'),  
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),  
        'redirect' => env('GOOGLE_REDIRECT_URI') 
    ],

    'apple' => [
        'client_id' => env('APPLE_CLIENT_ID'),
        'client_secret' => env('APPLE_CLIENT_SECRET'),
        'redirect' => env('APPLE_REDIRECT_URI'),
        'team_id' => env('APPLE_TEAM_ID'),
        'key_id' => env('APPLE_KEY_ID')
    ],

    'stripe' => [
        'private_key' => env('STRIPE_PRIVATE_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'webhook_secret_key' => env('STRIPE_WEBHOOK_SECRET_KEY'),
        'version' => env('STRIPE_VERSION', '2020-08-27'),
        'currency' => env('STRIPE_CURRENCY', 'USD')
    ]

];
