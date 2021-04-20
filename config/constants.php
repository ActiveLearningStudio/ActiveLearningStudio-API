<?php
return [
    'h5p-api-url' => env('H5P_API_URL'),
    'curriki-tsugi-host' => env('CURRIKI_TSUGI_HOST'),
    'front-url' => env('FRONT_END_URL'),
    'curriki-demo-email' => env('CURRIKI_DEMO_EMAIL'),
    'users' => [
        'sample-file' => 'sample/users-import-sample.csv'
    ],
    'public-organization-visibility-type-id' => env('PUBLIC_ORGANIZATION_VISIBILITY_TYPE_ID'),
    'global-organization-visibility-type-id' => env('GLOBAL_ORGANIZATION_VISIBILITY_TYPE_ID'),
    'indexing-approved' => env('INDEXING_APPROVED'),
    'indexing-options' => env('INDEXING_OPTIONS'),
    // for member role
    'member-role-id' => 3,

    /*
     |--------------------------------------------------------------------------
     | SERVER PROXY
     |--------------------------------------------------------------------------
     |
     | This value is for server proxy settings.
     | Possible Values: false|'string_url'
     */
    'server_proxy' => env('SERVER_PROXY', 'api'),
];
