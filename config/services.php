<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'sms' => [
        'template_code_a' => env('ALIYUN_SMS_TEMPLATE_CODE_A'),
        'template_code_b' => env('ALIYUN_SMS_TEMPLATE_CODE_B'),
        'template_code_c' => env('ALIYUN_SMS_TEMPLATE_CODE_C'),
    ],

    'wallet' => [
        'salt' => env('WALLET_SALT')
    ],

    'yunshichang' => [
        'app_code' => env('YUNSHICHANG_APP_CODE')
    ],
];
