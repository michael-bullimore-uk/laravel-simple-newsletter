<?php

return [
    'table_name' => 'subscribers',
    'model' => \MIBU\Newsletter\Models\Subscriber::class,
    'routes' => [
        'as' => 'newsletter.',
        'middleware' => [
            'throttle:newsletter',
        ],
        'prefix' => 'newsletter',
        'rate_limiter' => [
            'name' => 'newsletter', // Don't forget to rename refs.
            'per_min' => 20,
        ],
        'register' => true, // Set this to `false` to disable routes if you wish to explicitly define them
    ],
    'purge_subscribers_days' => 30, // Remove unverified subscribers
];
