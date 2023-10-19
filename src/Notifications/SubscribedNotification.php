<?php

namespace MIBU\Newsletter\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use MIBU\Newsletter\Models\Subscriber;

class SubscribedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Subscriber $subscriber)
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

        return (new \MIBU\Newsletter\Mail\Subscribed($this->subscriber))->to($address);
    }
}
