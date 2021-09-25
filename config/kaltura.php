<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Kaltura Service Url
    |--------------------------------------------------------------------------
    */
    'service_url' => env('Kaltura_SERVICE_URL', 'https://www.kaltura.com'),

    /*
    |--------------------------------------------------------------------------
    | Kaltura Secret
    |--------------------------------------------------------------------------
    |
    | Remember to provide the correct secret according to the sessionType you want
    |
    */
    'secret' => env('Kaltura_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Kaltura Parnter Id
    |--------------------------------------------------------------------------
    */
    'partner_id' => env('Kaltura_PARTNER_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Kaltura Expiry
    |--------------------------------------------------------------------------
    */
    'expiry' => env('Kaltura_EXPIRY', 86400),

    /*
    |--------------------------------------------------------------------------
    | Kaltura Privileges
    |--------------------------------------------------------------------------
    */
    'privileges' => env('Kaltura_PRIVILEGES', '*'),

    /*
    |--------------------------------------------------------------------------
    | Kaltura Session Type
    |--------------------------------------------------------------------------
    | Mean Kaltura Session Type. It may be 0 or 2, 0 for user and 2 for admin (https://www.kaltura.com/api_v3/testmeDoc/  |enums/KalturaSessionType.html)
    */
    'session_type' => env('Kaltura_SESSION_TYPE', '2'),

];
