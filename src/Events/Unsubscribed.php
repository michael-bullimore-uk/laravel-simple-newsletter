<?php

namespace MIBU\Newsletter\Events;

use MIBU\Newsletter\Models\Subscriber;

class Unsubscribed
{
    public function __construct(public Subscriber $subscriber)
    {
    }
}
