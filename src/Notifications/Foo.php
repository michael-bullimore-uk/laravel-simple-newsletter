<?php

namespace MIBU\Newsletter\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use MIBU\Newsletter\Models\Subscriber;

class Foo extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Subscriber $subscriber, public string $plainTextToken)
    {
    }

    public function via(object $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(object $notifiable): Mailable
    {
        $address = $notifiable instanceof AnonymousNotifiable
            ? $notifiable->routeNotificationFor('mail')
            : $notifiable->email;

        return (new \MIBU\Newsletter\Mail\Foo($this->subscriber, $this->plainTextToken))->to($address);
    }
}
