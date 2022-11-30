<?php

return [
    'client_id' => env('MSTEAMS_CLIENT_ID'),
    'secret_id' => env('MSTEAMS_SECRET_ID'),
    'tenant_id' => env('MSTEAMS_TENANT_ID'),
    'oauth_url' => env('MSTEAMS_OAUTH_URL'),
    'landing_url' => env('MSTEAMS_LANDING_URL'),
    'redirect_url' => env('MSTEAMS_REDIRECT_URL'),
    'assignment_due_days' => env('ASSIGNMENT_DUE_DATE_DAYS'),
    'scope' => 'https://graph.microsoft.com/.default',
];
 