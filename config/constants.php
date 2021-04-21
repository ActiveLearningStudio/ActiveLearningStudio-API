<?php
return [
    'h5p-api-url' => env('H5P_API_URL'),
    'curriki-tsugi-host' => env('CURRIKI_TSUGI_HOST'),
    'front-url' => env('FRONT_END_URL'),
    'curriki-demo-email' => env('CURRIKI_DEMO_EMAIL'),
    'users' => [
        'sample-file' => 'sample/users-import-sample.csv'
    ],
    'public-organization-visibility-type-id' => env('PUBLIC_ORGANIZATION_VISIBILITY_TYPE_ID', 4),
    'global-organization-visibility-type-id' => env('GLOBAL_ORGANIZATION_VISIBILITY_TYPE_ID', 3),
    'private-organization-visibility-type-id' => env('PRIVATE_ORGANIZATION_VISIBILITY_TYPE_ID', 1),
    'indexing-approved' => env('INDEXING_APPROVED', 3),
    'indexing-options' => env('INDEXING_OPTIONS', 'null,1,2,3'),
    // for member role
    'member-role-id' => 3,
    'default-pagination-limit-recent-projects' => env('DEFAULT_PAGINATION_LIMIT_RECENT_PROJECTS', 5),
    // for admin role
    'admin-role-id' => 1,
    'default-pagination-per-page' => env('DEFAULT_PAGINATION_PER_PAGE', 10),
    'default-date-format' => env('DEFAULT_DATE_FORMAT', 'd-M-Y'),

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
