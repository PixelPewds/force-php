<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Calendly Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file holds the Calendly integration settings.
    |
    */

    'calendly_url' => env('CALENDLY_URL', ''),

    'api_token' => env('CALENDLY_API_TOKEN', ''),

    'event_type' => env('CALENDLY_EVENT_TYPE', ''),

    'webhook_secret' => env('CALENDLY_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Schedule Call Settings
    |--------------------------------------------------------------------------
    */

    'allowed_statuses' => ['scheduled', 'completed', 'cancelled', 'pending'],

    'pagination' => [
        'per_page' => 15,
    ],
];
