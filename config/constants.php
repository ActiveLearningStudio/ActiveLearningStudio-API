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
    'protected-organization-visibility-type-id' => env('PROTECTED_ORGANIZATION_VISIBILITY_TYPE_ID', 2),
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

    // Safari Montage
    'sm-publisher-icon' => env('SAFARIMONTAGE_PUBLISHER_ICON', 'favicon-apple.png'),
    'sm-publisher-name' => env('SAFARIMONTAGE_PUBLISHER_NAME', 'CurrikiStudio'),
    // LTI Tool Consumer
    'tool-consumer-email' => env('LTI_TOOL_CONSUMER_EMAIL'),
    "tool-consumer-guid" => env('LTI_TOOL_CONSUMER_GUID', 'currikistudio.org'),
    "tool-consumer-description" => env('LTI_TOOL_CONSUMER_DESCRIPTION', 'curriki studio'),
    'tool-consumer-family-code' => env('LTI_TOOL_CONSUMER_FAMILY', 'currkistudio'),
    'tool-consumer-version' => env('LTI_TOOL_CONSUMER_VERSION', '1.0.0'),
    
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
