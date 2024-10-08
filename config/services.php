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


    'firebase' => [
        'token' => env('FIREBASE_TOKEN'),
    ],

    'FIREBASE_KEY' => [
        'token' => env('FIREBASE_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'twilio' => [
        'id' => env('twilio_id'),
        'token' => env('twilio_token'),
    ],

    'facebook' => [
        'client_id'     =>  env('facebook_id'),
        'client_secret' =>  env('facebook_secret'),
        'redirect'      => env('facebook_url'),
    ],

    'google' => [
        'client_id'     =>  env('google_id'),
        'client_secret' =>  env('google_secret'),
        'redirect'      =>  env('google_url'),
    ],

    'apple' => [
        'client_id'     =>  env('apple_id'),
        'client_secret' =>  env('apple_secret'),
        'redirect'      =>  env('apple_url'),
    ],
];
