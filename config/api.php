<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the REST API layer
    |
    */

    // API Version
    'version' => 'v1',

    // Default per-page for pagination
    'per_page' => 15,

    // Max per-page for pagination
    'max_per_page' => 100,

    // Default currency
    'currency' => env('API_CURRENCY', 'EUR'),

    // Token expiration (in minutes)
    'token_expiration' => 1440, // 24 hours

    // Refresh token expiration (in minutes)
    'refresh_token_expiration' => 10080, // 7 days

    // Rate limiting
    'rate_limit' => [
        'authenticated' => 1000, // per minute
        'unauthenticated' => 60, // per minute
    ],

    // Response headers
    'headers' => [
        'X-API-Version' => 'v1',
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
    ],
];
