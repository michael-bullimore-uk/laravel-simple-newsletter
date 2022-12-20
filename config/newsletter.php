<?php

return [
    'table_name' => 'subscribers',
    'model' => \MIBU\Newsletter\Models\Subscriber::class,
    'rate_limiter' => [
        'name' => 'newsletter',
        'per_min' => 20,
    ],
    'purge_subscribers_days' => 30, // Remove unverified subscribers
];
