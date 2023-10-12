<?php

return [
    'table_name' => 'subscribers',
    'model' => \MIBU\Newsletter\Models\Subscriber::class,
    'routes' => [
        'rate_limiter' => [
            'name' => 'newsletter', // Don't forget to rename any applicable middleware.
            'per_min' => 20,
        ],
        'middleware' => [
            'throttle:newsletter',
        ],
        'name_prefix' => 'newsletter', // newsletter.subscribe
        'prefix' => 'newsletter', // /newsletter/subscribe
    ],
    'purge_subscribers_days' => 30, // Remove unverified subscribers
];
