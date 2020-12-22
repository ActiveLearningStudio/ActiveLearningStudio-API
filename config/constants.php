<?php
return [
    'h5p-api-url' => env('H5P_API_URL'),
    'curriki-tsugi-host' => env('CURRIKI_TSUGI_HOST'),
    'front-url' => env('FRONT_END_URL'),
    'curriki-demo-email' => env('CURRIKI_DEMO_EMAIL'),
    'users' => [
        'sample-file' => 'sample/users-import-sample.csv'
    ],

    /*
     |--------------------------------------------------------------------------
     | SERVER PROXY
     |--------------------------------------------------------------------------
     |
     | This value is for server proxy settings.
     | Possible Values: false|'string_url'
     */
    'server_proxy' => env('SERVER_PROXY', 'api'),

    /*
     |--------------------------------------------------------------------------
     | H5P STORAGE
     |--------------------------------------------------------------------------
     |
     | This value is to enable S3 storage for H5P
     | Possible Values: false|true
     */
    'enable_s3_h5p' => env('ENABLE_S3_H5P', false),
];
