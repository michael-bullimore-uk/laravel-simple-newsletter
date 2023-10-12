<?php

namespace MIBU\Newsletter\Events;

use MIBU\Newsletter\Models\Subscriber;

class Subscribed
{
    public function __construct(public Subscriber $subscriber)
    {
    }
}
