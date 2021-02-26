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

    'twitter' => [
        'consumer_key'    => env('TWITTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
        'access_token'    => env('TWITTER_ACCESS_TOKEN'),
        'access_secret'   => env('TWITTER_ACCESS_SECRET')
    ],

    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN', '992880250:AAHxFJrhcOionjhPwvNegrAwiZDCWhaEn4U')
    ],
    // 'facebook' => [
    //     'page-token' => env('FACEBOOK_PAGE_TOKEN', 'YOUR PAGE TOKEN HERE'),
    //     'version'    => env('FACEBOOK_GRAPH_API_VERSION', 'v7.0'),
    //     'app-secret' => env('FACEBOOK_APP_SECRET', '6420bd7f5c63da6a833fb57628beb0c1')
    // ],
    'facebook_poster' => [
        'client_id' => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'access_token' => env('FACEBOOK_ACCESS_TOKEN'),
    ],


    'facebook' => [
        'client_id'     => '262197578238257',
        'client_secret' => 'ee5eb8840fdef3029f2957ab616f84e0',
        'redirect'      => '/oauth/facebook/callback',
    ]
];
