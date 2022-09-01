<?php

return [
    'paths' => [
        "/login" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Login User",
                "description" => "Login User",
                "operationId" => "login",
                "consumes" => [
                    "application/json"
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "in" => "header",
                        "name" => "device-type",
                        "description" => "device-type",
                        "required" => true
                    ],
                    [
                        "in" => "header",
                        "name" => "app-version",
                        "description" => "app-version",
                        "required" => true
                    ],
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Login for accounts",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/login"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],
        "/sign-up" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Sign Up User",
                "description" => "Sign Up User",
                "operationId" => "sign up",
                "consumes" => [
                    'multipart/form-data'
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                  
                    [
                        "in" => "formData",
                        "name" => "profile_image",
                        "description" => "profile image",
                        "required" => false,
                        "type" => "file",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "first_name",
                        "description" => "First Name",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "last_name",
                        "description" => "Last Name",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "email",
                        "description" => "Email",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "phone_number",
                        "description" => "Phone Number",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "address",
                        "description" => "Address",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "zip_code",
                        "description" => "Zip code",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "country",
                        "description" => "Country",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "country_code",
                        "description" => "Country code",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "country_iso_code",
                        "description" => "Country Iso Code",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "country_short_name",
                        "description" => "Country Short Name",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "state",
                        "description" => "State",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "city",
                        "description" => "City",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "lat",
                        "description" => "Latitude",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "lng",
                        "description" => "Longitude",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "password",
                        "description" => "Password",
                        "required" => true,
                        "type" => "string",
                    ],

                ],
                "responses" => [
                ]
            ]
        ],   
        "/otp-verification" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Sign up otp verification",
                "description" => "Otp verification",
                "operationId" => "otp verification",
                "consumes" => [
                    "application/json"
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "in" => "header",
                        "name" => "device-type",
                        "description" => "device-type",
                        "required" => true
                    ],
                    [
                        "in" => "header",
                        "name" => "app-version",
                        "description" => "app-version",
                        "required" => true
                    ],
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Sign up otp verification",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/otpVerification"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],
        "/resend-otp" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Resend Otp",
                "description" => "Resend Otp",
                "operationId" => "resend otp",
                "consumes" => [
                    "application/json"
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Login for accounts",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/resendOtp"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],
        "/social-login" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Social Login",
                "description" => "Social Login",
                "operationId" => "social-login",
                "consumes" => [
                    "application/json"
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "in" => "header",
                        "name" => "device-type",
                        "description" => "device-type",
                        "required" => true
                    ],
                    [
                        "in" => "header",
                        "name" => "app-version",
                        "description" => "app-version",
                        "required" => true
                    ],
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Social Login",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/socialLogin"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],    
        "/edit-account" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Edit Account",
                "description" => "Edit Account",
                "operationId" => "edit-account",
                "consumes" => [
                    'multipart/form-data'
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "in" => "header",
                        "name" => "authorization",
                        "description" => "authorization",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                  
                    [
                        "in" => "formData",
                        "name" => "profile_image",
                        "description" => "profile image",
                        "required" => false,
                        "type" => "file",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "first_name",
                        "description" => "First Name",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "last_name",
                        "description" => "Last Name",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "email",
                        "description" => "Email",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "phone_number",
                        "description" => "Phone Number",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "address",
                        "description" => "Address",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "zip_code",
                        "description" => "Zip code",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "country",
                        "description" => "Country",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "country_code",
                        "description" => "Country code",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "country_iso_code",
                        "description" => "Country iso code",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "country_short_name",
                        "description" => "Country Short Name",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "state",
                        "description" => "State",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "city",
                        "description" => "City",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "lat",
                        "description" => "Latitude",
                        "required" => true,
                        "type" => "string",
                    ],
                    [
                        "in" => "formData",
                        "name" => "lng",
                        "description" => "Longitude",
                        "required" => true,
                        "type" => "string",
                    ],

                ],
                "responses" => [
                ]
            ]
        ], 
        "/forgot-password" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Forgot Password",
                "description" => "Forgot Password",
                "operationId" => "forgot password",
                "consumes" => [
                    "application/json"
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Forgot Password",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/forgotPassword"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ], 
        "/forgot-password-otp-verification" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Forgot Password Otp Verification",
                "description" => "Forgot Password Otp Verification",
                "operationId" => "forgot password otp verification",
                "consumes" => [
                    "application/json"
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Forgot Password Otp Verification",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/forgotPasswordOtpVerification"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ], 
        "/reset-forgot-password" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Reset Forgot Password",
                "description" => "Reset Forgot Password",
                "operationId" => "reset forgot password",
                "consumes" => [
                    "application/json"
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Reset Forgot Password",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/resetForgotPassword"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ], 
        "/change-password" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Change Password",
                "description" => "Change Password",
                "operationId" => "change password",
                "consumes" => [
                    "application/json"
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "in" => "header",
                        "name" => "authorization",
                        "description" => "authorization",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                  
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Change Password",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/changePassword"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],
        "/logout" => [
            "post" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Logout User",
                "description" => "Logout User",
                "operationId" => "logout",
                "consumes" => [
                    "application/json"
                ],
                "parameters" => [
                        [
                        "name" => "authorization",
                        "in" => "header",
                        "description" => "Access Token",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ],
        ],
        "/get-account-detail" => [
            "get" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Get Account Detail",
                "description" => "Get Account Detail",
                "operationId" => "Account Detail",
                "consumes" => [
                    "application/json"
                ],
                "parameters" => [
                        [
                        "name" => "authorization",
                        "in" => "header",
                        "description" => "Access Token",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ],
        ],
        "/notifications" => [
            "get" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Get notification list",
                "description" => "Get notification list",
                "operationId" => "notifications",
                "consumes" => [
                    "application/json"
                ],
                "parameters" => [
                        [
                        "name" => "authorization",
                        "in" => "header",
                        "description" => "Access Token",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ],
        ],
        "/dismiss-notification/{notificationId}" => [
            "delete" => [
                "tags" => [
                    "account"
                ],
                "summary" => "Dismiss notification",
                "description" => "Dismiss notification",
                "operationId" => "dismiss-notification",
                "consumes" => [
                    "application/json",
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "name" => "authorization",
                        "in" => "header",
                        "description" => "Access Token",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "path",
                        "name" => "notificationId",
                        "description" => "Dismiss Notification",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ],
        ],
      
    ],
    'definitions' => [
        'login' => [
            'type' => "object",
            'properties' => [
                'device_token' => [
                    'type' => 'string'
                ],
                'email' => [
                    'type' => 'string'
                ],
                'password' => [
                    'type' => 'string'
                ],

            ],
            'xml' => [
                'name' => "login"
            ]
        ],      
        'otpVerification' => [
            'type' => "object",
            'properties' => [
                'device_token' => [
                    'type' => 'string'
                ],
                'email' => [
                    'type' => 'string'
                ],
                'otp' => [
                    'type' => 'string'
                ],

            ],
            'xml' => [
                'name' => "otp-verification"
            ]
        ],  
        'resendOtp' => [
            'type' => "object",
            'properties' => [
                'email' => [
                    'type' => 'string'
                ],
                'email_type' => [
                    'type' => 'string',
                    "enum" => ['sign-up','forgot-password','login'] 
                ],

            ],
            'xml' => [
                'name' => "otp-verification"
            ]
        ],  
        'socialLogin' => [
            'type' => "object",
            'properties' => [
                'device_token' => [
                    'type' => 'string'
                ],
                'login_type' => [
                    'type' => 'string',
                    "enum" => ['facebook','google','apple'] 
                ],
                'auth_token' => [
                    'type' => 'string'
                ],

            ],
            'xml' => [
                'name' => "social-login"
            ]
        ],
        'forgotPassword' => [
            'type' => "object",
            'properties' => [
                'email' => [
                    'type' => 'string'
                ],
            ],
            'xml' => [
                'name' => "forgot-password"
            ]
        ],
        'forgotPasswordOtpVerification' => [
            'type' => "object",
            'properties' => [
                'otp' => [
                    'type' => 'string'
                ],
                'email' => [
                    'type' => 'string'
                ],
            ],
            'xml' => [
                'name' => "forgot-password"
            ]
        ],
        'resetForgotPassword' => [
            'type' => "object",
            'properties' => [
                'password' => [
                    'type' => 'string'
                ],
                'password_confirmation' => [
                    'type' => 'string'
                ],
                'email' => [
                    'type' => 'string'
                ],
            ],
            'xml' => [
                'name' => "reset-forgot-password"
            ]
        ],
        'changePassword' => [
            'type' => "object",
            'properties' => [
                'current_password' => [
                    'type' => 'string'
                ],
                'new_password' => [
                    'type' => 'string'
                ],
                'confirm_password' => [
                    'type' => 'string'
                ],
            ],
            'xml' => [
                'name' => "change-password"
            ]
        ],
        'dismissNotification' => [
            'type' => "object",
            'properties' => [
                'notificationId ' => [
                    'type' => 'string'
                ]
            ],
            'xml' => [
                'name' => "change-password"
            ]
        ]   
    ]
];
