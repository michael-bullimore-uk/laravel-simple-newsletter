<?php

namespace MIBU\Newsletter\Events;

use MIBU\Newsletter\Models\Subscriber;

class SubscriberVerified
{
    public function __construct(public Subscriber $subscriber)
    {
    }
}
