<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stemuli' => [
        'client' => env('STEMULI_CLIENT_ID'),
        'secret' => env('STEMULI_SECRET_KEY'),
        'basic_url' => env('STEMULI_BASIC_URI', 'https://apidev.stemuli.net/api/v1/auth0/authorize'),
        'response_type' => env('STEMULI_RESPONSE_TYPE', 'code'),
        'redirect_uri' => env('STEMULI_REDIRECT_URI', config('app.url').'/api/oauth/stemuli/callback'),
        'scope' => env('STEMULI_SCOPE', 'openid'),
        'token_url' => env('STEMULI_TOKEN_URL', 'https://apidev.stemuli.net/api/v1/auth0/token'),
    ],

];
