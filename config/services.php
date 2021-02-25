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
        'key' => 'AKIA5MOHZKSAJPF3SOEM',
        'secret' => '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',
        'region' => 'eu-west-1',
    ],

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'public' => env('STRIPE_KEY')
    ],
	

    'getaddress' => [
        'key' => 'fZhecP76tkifpf0u6ryAxw12264'
    ],

];
