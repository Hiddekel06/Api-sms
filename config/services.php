<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'yas_sms' => [
        'base_url' => env('YAS_SMS_BASE_URL', 'https://api.yasbusiness.sn'),
        'username' => env('YAS_SMS_USERNAME'),
        'password' => env('YAS_SMS_PASSWORD'),
        'default_from' => env('YAS_SMS_DEFAULT_FROM', 'E-fPublique'),
        'verify_ssl' => env('YAS_SMS_VERIFY_SSL', false),
        'bearer_tokens' => array_values(array_filter(array_map('trim', explode(',', (string) env('SMS_API_BEARER_TOKENS', env('SMS_API_BEARER_TOKEN', '')))))),
        'default_token' => env('SMS_API_BEARER_TOKEN', ''),
    ],

];
