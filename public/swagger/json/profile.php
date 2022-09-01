<?php

return [
    'paths' => [
        "/get-profile-list" => [
            "get" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Get profile list",
                "description" => "Get profile list",
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
        "/get-profile/{profileId}" => [
            "get" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Get Profile",
                "description" => "Get Profile",
                "operationId" => "get profile",
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
                        "in" => "path",
                        "name" => "profileId",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ], 
        "/upload-profile-cover-image" => [
            "post" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Upload Profile Image",
                "description" => "Upload Profile Image",
                "operationId" => "upload profile image",
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
                        "name" => "profileId",
                        "description" => "Profile ID",
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
                        "name" => "banner_image",
                        "description" => "Banner image",
                        "required" => false,
                        "type" => "file",
                        "format" => "int64"
                    ],   
                ],
                "responses" => [
                ]
            ]
        ], 
        "/edit-profile-detail/{profileId}" => [
            "put" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Edit Profile Detail",
                "description" => "Edit Profile Detail",
                "operationId" => "edit profile detail",
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
                        "description" => "Authorization",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "path",
                        "name" => "profileId",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "edit profile detail",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/editProfileDetail"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ], 
        "/edit-profile-journey/{profileId}" => [
            "put" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Edit Profile Journey",
                "description" => "Edit Profile Journey",
                "operationId" => "edit profile journey",
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
                        "description" => "Authorization",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "path",
                        "name" => "profileId",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "edit profile journey",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/editProfileJourney"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],
        "/upload-media-image" => [
            "post" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Upload Media Image",
                "description" => "Upload Media Image",
                "operationId" => "upload media image",
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
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "image",
                        "description" => "Image",
                        "required" => false,
                        "type" => "file",
                        "format" => "int64"
                    ],  
                    [
                        "in" => "formData",
                        "name" => "caption",
                        "description" => "Image caption",
                        "required" => false,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "position",
                        "description" => "Media Position",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                     
                ],
                "responses" => [
                ]
            ]
        ],
        "/update-media-image" => [
            "post" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Update profile media image and caption",
                "description" => "update profile media image and caption",
                "operationId" => "update profile media image and caption",
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
                        "description" => "Authorization",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "id",
                        "description" => "media id",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "image",
                        "description" => "Image",
                        "required" => false,
                        "type" => "file",
                        "format" => "int64"
                    ],    
                ],
                "responses" => [
                ]
            ]
        ], 
        "/delete-media" => [
            "delete" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Delete Profile Media",
                "description" => "delete profile media",
                "operationId" => "change",
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
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "id",
                        "description" => "Media ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                    "default" => [
                        "description" => "successful operation"
                    ]
                ]
            ]
        ],
        "/update-media-position/{profileId}" => [
            "put" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Update media position",
                "description" => "Update media position",
                "operationId" => "Update media position",
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
                        "description" => "Authorization",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "path",
                        "name" => "profileId",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Media positon array",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/updateMediaPosition"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ], 
        "/upload-media-video" => [
            "post" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Upload Profile Media Video",
                "description" => "Upload Profile Media Video",
                "operationId" => "upload profile media video",
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
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "video",
                        "description" => "Video",
                        "required" => false,
                        "type" => "file",
                        "format" => "int64"
                    ],  
                    [
                        "in" => "formData",
                        "name" => "duration",
                        "description" => "Video duration",
                        "required" => false,
                        "type" => "string",
                        "format" => "int64"
                    ],   
                    [
                        "in" => "formData",
                        "name" => "caption",
                        "description" => "Video caption",
                        "required" => false,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "position",
                        "description" => "Video position",
                        "required" => false,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/update-media-video" => [
            "post" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Update Profile Media Video",
                "description" => "Update Profile Media Video",
                "operationId" => "Update profile media video",
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
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "id",
                        "description" => "Media ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "video",
                        "description" => "Video",
                        "required" => false,
                        "type" => "file",
                        "format" => "int64"
                    ],  
                    [
                        "in" => "formData",
                        "name" => "duration",
                        "description" => "Video duration",
                        "required" => false,
                        "type" => "string",
                        "format" => "int64"
                    ],   
                ],
                "responses" => [
                ]
            ]
        ],
        "/upload-voice-note" => [
            "post" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Upload Voice Note",
                "description" => "Upload Voice Note",
                "operationId" => "upload voice note",
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
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "audio",
                        "description" => "Audio",
                        "required" => false,
                        "type" => "file",
                        "format" => "int64"
                    ],     
                    [
                        "in" => "formData",
                        "name" => "caption",
                        "description" => "Audio caption",
                        "required" => false,
                        "type" => "string",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/add-stories-articles" => [
            "post" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Add Profile Stories & Article",
                "description" => "Add Profile Stories & Article",
                "operationId" => "add profile stories & article",
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
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "image",
                        "description" => "Image",
                        "required" => true,
                        "type" => "file",
                        "format" => "int64"
                    ],     
                    [
                        "in" => "formData",
                        "name" => "title",
                        "description" => "Stories Articles Title",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "text",
                        "description" => "Stories Articles Article",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/update-stories-articles-media" => [
            "post" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Update Profile Stories & Article Media File",
                "description" => "Update Profile Stories & Article Media File",
                "operationId" => "update profile stories & article media file",
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
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "image",
                        "description" => "Image",
                        "required" => true,
                        "type" => "file",
                        "format" => "int64"
                    ],     
                    [
                        "in" => "formData",
                        "name" => "id",
                        "description" => "Stories Articles Media id",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/delete-stories-articles" => [
            "delete" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Delete Profile Stories and Article",
                "description" => "Delete Profile Stories and Articles",
                "operationId" => "delete profile ptories and articles",
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
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "id",
                        "description" => "Stories Article Media ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                    "default" => [
                        "description" => "successful operation"
                    ]
                ]
            ]
        ],
        "/update-stories-articles-position/{profileId}" => [
            "put" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Update Stories & Article position",
                "description" => "Update stories and article position",
                "operationId" => "Update stories and article position",
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
                        "description" => "Authorization",
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                    [
                        "in" => "path",
                        "name" => "profileId",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Stories & Article positon array",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/updateStoriesArticlePosition"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ], 
        "/add-update-grave-site-image" => [
            "post" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Add Update Grave Site Image",
                "description" => "Add Update Grave Site Image",
                "operationId" => "add update grave site image",
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
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "formData",
                        "name" => "image",
                        "description" => "Image",
                        "required" => false,
                        "type" => "file",
                        "format" => "int64"
                    ],      
                ],
                "responses" => [
                ]
            ]
        ],
        "/delete-grave-site-image" => [
            "delete" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Delete Profile Grave Site Image",
                "description" => "Delete Profile Grave Site Image",
                "operationId" => "delete profile grave site image",
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
                        "name" => "profile_id",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "id",
                        "description" => "Grave Site ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/add-grave-site-location" => [
            "post" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Add Grave Site Location",
                "description" => "Add Grave Site Location",
                "operationId" => "add grave site location",
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
                        "name" => "profileId",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Add Update Grave Site Location",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/addGraveSiteLocation"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],
        "/update-grave-location/{profileId}" => [
            "put" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Update Grave Site Location",
                "description" => "Update Grave Site Location",
                "operationId" => "Update grave site location",
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
                        "in" => "path",
                        "name" => "profileId",
                        "description" => "Profile ID",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "body",
                        "name" => "body",
                        "description" => "Add Update Grave Site Location",
                        "required" => false,
                        "schema" => [
                            '$ref' => "#/definitions/addGraveSiteLocation"
                        ]
                    ]
                ],
                "responses" => [
                ]
            ]
        ],
        "/get-profile-media" => [
            "get" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Get Profile Media (Image , Video , Audio)",
                "description" => "Get Profile Media (Image , Video , Audio)",
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
                        "name" => "page",
                        "description" => "Page No",
                        "required" => false,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "profile_id",
                        "description" => "Profile Id",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                    [
                        "in" => "query",
                        "name" => "type",
                        "description" => "Media Type",
                        'enum' => ['image', 'video', 'audio'],
                        "required" => true,
                        "type" => "string",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/get-profile-stories_articles" => [
            "get" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Get Profile Stories and Articles",
                "description" => "Get Profile Stories and Articles",
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
                        "name" => "page",
                        "description" => "Page No",
                        "required" => false,
                        "type" => "integer",
                        "format" => "int64"
                    ],                    
                    [
                        "in" => "query",
                        "name" => "profile_id",
                        "description" => "Profile Id",
                        "required" => true,
                        "type" => "integer",
                        "format" => "int64"
                    ],
                ],
                "responses" => [
                ]
            ]
        ],
        "/get-profile-guest-book" => [
            "get" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Get Profile Guest Book",
                "description" => "Get Profile Guest Book",
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
                        "name" => "profile_id",
                        "description" => "Profile Id",
                        "required" => true,
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
                ],
                "responses" => [
                ]
            ]
        ],
        "/guest-book-you-signed" => [
            "get" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Get User Signed Book",
                "description" => "Get User Signed Book",
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
        "/get-default-profile-data" => [
            "get" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Get Profile Default Data",
                "description" => "Get Profile Default Data",
                "operationId" => "get",
                "consumes" => [
                    "application/json"
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                ],
                "responses" => [
                ]
            ]
        ],
        "/get-guest-profile/{profileId}" => [
            "get" => [
                "tags" => [
                    "profile"
                ],
                "summary" => "Get Profile",
                "description" => "Get Profile",
                "operationId" => "get profile",
                "consumes" => [
                    "application/json"
                ],
                "produces" => [
                    "application/json"
                ],
                "parameters" => [
                    [
                        "in" => "path",
                        "name" => "profileId",
                        "description" => "Profile ID",
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
        'editProfileDetail' => [
            'type' => "object",
            'properties' => [
                'profile_name' => [
                    'type' => 'string'
                ],
                'gender' => [
                    'type' => 'string',
                    "enum" => ['male','female','other'] 
                ],
                'date_of_birth' => [
                    'type' => 'string',
                    "description" => "Date(YYYY-MM-DD)",
                ],
                'date_of_death' => [
                    'type' => 'string',
                    "description" => "Date(YYYY-MM-DD)",
                ],
                'short_description' => [
                    'type' => 'string'
                ],
            ],
            'xml' => [
                'name' => "edit-profile-detail"
            ]
        ],
        'editProfileJourney' => [
            'type' => "object",
            'properties' => [
                'journey' => [
                    'type' => 'string'
                ],
            ],
            'xml' => [
                'name' => "edit-profile-journey"
            ]
        ],
        'updateMediaPosition' => [
            'type' => "object",
            'properties' => [
                'images_array' => [
                    'type' => 'array',
                    "example" => [['position'=> 1, 'id'=> 673 ,'caption' => 'This is test caption one'],['position'=> 2, 'id'=> 674 ,'caption' => 'This is test caption two']]
                ],
            ],
            'xml' => [
                'name' => "update-media-position"
            ]
        ],
        'updateStoriesArticlePosition' => [
            'type' => "object",
            'properties' => [
                'stories_articles_array' => [
                    'type' => 'array',
                    "example" => [['position'=> 1, 'id'=> 673 ,'title' => 'This is test title one', 'text' => 'This is test text' ],['position'=> 2, 'id'=> 674 ,'title' => 'This is test title two', 'text' => 'this is test text']]
                ],
            ],
            'xml' => [
                'name' => "update-media-position"
            ]
        ],
        'addGraveSiteLocation' => [
            'type' => "object",
            'properties' => [
                'address' => [
                    'type' => 'string'
                ],
                'country' => [
                    'type' => 'string'
                ],
                'state' => [
                    'type' => 'string'
                ],
                'city' => [
                    'type' => 'string'
                ],
                'zip_code' => [
                    'type' => 'string'
                ],
                'note' => [
                    'type' => 'string'
                ],
                'lat' => [
                    'type' => 'string'
                ],
                'lang' => [
                    'type' => 'string'
                ],

            ],
            'xml' => [
                'name' => "addGraveSiteLocation"
            ]
        ], 
        
    ]
];

