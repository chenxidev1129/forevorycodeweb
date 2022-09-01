<?php

return [
    'paths' => [
        "/get-save-card" => [
            "get" => [
                "tags" => [
                    "Payment Method"
                ],
                "summary" => "Get User Card Detail",
                "description" => "Get User Card Detail",
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
        "/add-card" => [
            "post" => [
                "tags" => [
                    "Payment Method"
                ],
                "summary" => "Add User Card Detail",
                "description" => "Add User Card Detail",
                "operationId" => "add user card detail",
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
                        "in" => "body",
                        "name" => "body",
                        "description" => "User Card Detail",
                        "required" => true,
                        "schema" => [
                            '$ref' => "#/definitions/addCard"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],
        "/delete-card" => [
            "delete" => [
                "tags" => [
                    "Payment Method"
                ],
                "summary" => "Delete card Detail",
                "description" => "Delete card Detail",
                "operationId" => "delete card detail",
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
                        "description" => "Authorization",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "id",
                        "description" => "Card ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/make-card-default" => [
            "post" => [
                "tags" => [
                    "Payment Method"
                ],
                "summary" => "Make card Default",
                "description" => "Make card Default",
                "operationId" => "Make card Default",
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
                        "description" => "Authorization",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "id",
                        "description" => "Card ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
    ],
    'definitions' => [
        'addCard' => [
            'type' => "object",
            'properties' => [
                'token' => [
                    'type' => 'string'
                ],
            ],
            'xml' => [
                'name' => "Add User Card Detail"
            ]
        ],  
    ]
];
