<?php

return [
    'error_bag' => 'newsletter',
    'model' => \MIBU\Newsletter\Models\Subscriber::class,
    'purge_stale_subscribers_days' => 30, // Remove stale ("old") unverified subscribers
    'routes' => [
        'as' => 'newsletter.',
        'middleware' => [
            'web',
            'throttle:newsletter',
        ],
        'prefix' => 'newsletter',
        'rate_limiter' => [
            'name' => 'newsletter', // Don't forget to rename refs.
            'per_min' => 20,
        ],
        'register' => true, // Set this to `false` to disable routes if you wish to explicitly define them
    ],
    'table_name' => 'subscribers',
];
