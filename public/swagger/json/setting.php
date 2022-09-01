<?php

return [
    'paths' => [
        "/get-setting" => [
            "get" => [
                "tags" => [
                    "setting"
                ],
                "summary" => "Get Setting",
                "description" => "Get Setting",
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
    ],
    'definitions' => [
    ]
];
