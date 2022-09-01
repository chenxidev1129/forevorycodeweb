<?php

$params = require_once('params.php');

$account = require_once('account.php');

$subscription = require_once('subscription.php');

$profile = require_once('profile.php');

$paymentMethod = require_once('paymentMethod.php');

$setting = require_once('setting.php');

$paths = array_merge($account['paths'], $subscription['paths'], $profile['paths'], $paymentMethod['paths'], $setting['paths']);

$definitions = array_merge($account['definitions'], $subscription['definitions'], $profile['definitions'], $paymentMethod['definitions'], $setting['definitions']);

echo json_encode([
    'tags' => [
        [
            'name' => 'account',
            'description' => 'Guest user operations.',
        ], 
        [
            'name' => 'subscription',
            'description' => 'User subscription.',
        ], 
        [
            'name' => 'profile',
            'description' => 'User profile.',
        ], 
        [
            'name' => 'Payment Method',
            'description' => 'User Payment Method.',
        ], 
        [
            'name' => 'setting',
            'description' => 'setting.',
        ] 
    ],
    "swagger" => "1.0",
    "info" => [
        "version" => "1.0.0",
        "title" => "Forevory"
    ],
    "host" => $params['host'],
    "basePath" => $params['basePath'],
    "schemes" => [
        "https",
        "http"
    ],
    'paths' => $paths,
    'definitions' => $definitions
]);
