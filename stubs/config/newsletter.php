<?php

return [
    'table_name' => 'subscribers',
    'model' => \MIBU\Newsletter\Models\Subscriber::class,
    'rate_limiter_name' => 'newsletter',
    'purge_subscribers_days' => 30,
];
