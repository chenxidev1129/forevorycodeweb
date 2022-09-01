<?php

return [
    'paths' => [
        "/get-subscription-plan" => [
            "get" => [
                "tags" => [
                    "subscription"
                ],
                "summary" => "Get subscription list",
                "description" => "Get subscription list",
                "operationId" => "get",
                "consumes" => [
                    "application/json"
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
                ],
                "responses" => [
                ]
            ]
        ],
        "/subscription-checkout" => [
            "post" => [
                "tags" => [
                    "subscription"
                ],
                "summary" => "Subscription Checkout",
                "description" => "Subscription Checkout",
                "operationId" => "subscription checkout",
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
                        "description" => "subscription checkout",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/subscriptionCheckout"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],
        "/get-transactions" => [
            "get" => [
                "tags" => [
                    "subscription"
                ],
                "summary" => "Get User Transection List",
                "description" => "Get User Transection List",
                "operationId" => "get",
                "consumes" => [
                    "application/json"
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
                        "in" => "query",
                        "name" => "page_limit",
                        "description" => "Page Limit",
                        "required" => false,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "search",
                        "description" => "Search",
                        "required" => false,
                        "type" => "string",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/get-subscriptions" => [
            "get" => [
                "tags" => [
                    "subscription"
                ],
                "summary" => "Get Profile Subscription List",
                "description" => "Get Profile Subscription List",
                "operationId" => "get profile subscription list",
                "consumes" => [
                    "application/json"
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
                        "in" => "query",
                        "name" => "page_limit",
                        "description" => "Page Limit",
                        "required" => false,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "page",
                        "description" => "Page No",
                        "required" => false,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "search",
                        "description" => "Search",
                        "required" => false,
                        "type" => "string",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/get-switch-plan" => [
            "get" => [
                "tags" => [
                    "subscription"
                ],
                "summary" => "Get Switch plan",
                "description" => "Get Switch plan",
                "operationId" => "get",
                "consumes" => [
                    "application/json"
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
                    "in" => "query",
                    "name" => "id",
                    "description" => "Subscription ID",
                    "required" => true,
                    "type" => "integer",
                    "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/get-buy-now-plan" => [
            "get" => [
                "tags" => [
                    "subscription"
                ],
                "summary" => "Get Buy Now Plan",
                "description" => "Get Buy Now Plan",
                "operationId" => "get",
                "consumes" => [
                    "application/json"
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
                ],
                "responses" => [
                ]
            ]
        ],
        "/cancel-subscription" => [
            "post" => [
                "tags" => [
                    "subscription"
                ],
                "summary" => "Cancel Profile Subscription",
                "description" => "Cancel Profile Subscription",
                "operationId" => "Cancel Profile Subscription",
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
                        "in" => "query",
                        "name" => "id",
                        "description" => "Subscription ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/switch-subscription" => [
            "post" => [
                "tags" => [
                    "subscription"
                ],
                "summary" => "Switch Profile Subscription",
                "description" => "Switch Profile Subscription",
                "operationId" => "Switch Profile Subscription",
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
                        "in" => "query",
                        "name" => "id",
                        "description" => "Subscription ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "plan_id",
                        "description" => "Subscription Plan ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/buy-subscription" => [
            "post" => [
                "tags" => [
                    "subscription"
                ],
                "summary" => "Buy Profile Subscription",
                "description" => "Buy Profile Subscription",
                "operationId" => "Buy Profile Subscription",
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
                        "in" => "query",
                        "name" => "id",
                        "description" => "Subscription ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "plan_id",
                        "description" => "Subscription Plan ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "card_id",
                        "description" => "Subscription Card ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/save-transaction-detail" => [
            "post" => [
                "tags" => [
                    "subscription"
                ],
                "summary" => "Save transaction detail",
                "description" => "Save transaction detail",
                "operationId" => "transaction save",
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
                        "description" => "transaction save",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/saveTransactionDetail"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],
    ],
    'definitions' => [
        'subscriptionCheckout' => [
            'type' => "object",
            'properties' => [
                'card_token' => [
                    'type' => 'string',
                ],
                'card_type' => [
                    'type' => 'string',
                    "description" => "addNewCard or saveCard",
                ],
                'email' => [
                    'type' => 'string'
                ],
                'subscription_id' => [
                    'type' => 'string'
                ],
                'terms_condition' => [
                    'type' => 'string'
                ],
            ],
            'xml' => [
                'name' => "subscription-checkout"
            ]
        ],
        'saveTransactionDetail' => [
            'type' => "object",
            'properties' => [
                
            ],
            'xml' => [
                'name' => "transaction detail"
            ]
        ]
    ]
];
