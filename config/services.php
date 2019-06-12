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
    'ejScreenApi' => [
      'endpoint' => [
        'uri' => "https://ejscreen.epa.gov/mapper/ejscreenRESTbroker.aspx?geometry={%22x%22:",
        'lng_query' => ",%22y%22:",
        'lat_query' => ",%22spatialReference%22:{%22wkid%22:4326}}&distance=1&unit=9035&areatype=&areaid=&f=pjson",
      ]
    ],

    'fipscoder' => [
      'endpoint' => [
        'uri' => "https://geo.fcc.gov/api/census/block/find?latitude=",
        'lng_query' => "&showall=false&format=json",
        'lat_query' => "&longitude=",
      ],
      'key' => env('GEOCODER_KEY'),
    ],

    'geocoder' => [
      'endpoint' => env('GEOCODER_URI'),
      'key' => env('GEOCODER_KEY'),
    ],

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

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

];
