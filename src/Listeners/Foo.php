<?php

namespace MIBU\Newsletter\Listeners;

use Illuminate\Support\Facades\Notification;
use MIBU\Newsletter\Events\Subscribed;

class Foo
{
    public function handle(Subscribed $event): void
    {
        Notification::route('mail', $event->subscriber->email)
            ->notify(new \MIBU\Newsletter\Notifications\Foo($event->subscriber, $event->plainTextToken));
    }

}
