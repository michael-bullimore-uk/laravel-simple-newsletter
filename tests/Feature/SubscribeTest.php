<?php

namespace MIBU\Newsletter\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use MIBU\Newsletter\Events\Subscribed;
use MIBU\Newsletter\Listeners\SendSubscribedNotification;
use MIBU\Newsletter\Tests\TestCase;

class SubscribeTest extends TestCase
{
    public function test_subscribe_json()
    {
        $email = 'john@smith.com';
        $response = $this->json('post', '/subscribe', [
            'email' => $email,
        ]);

        $response->assertExactJson([]);
        $response->assertCreated();

        $this->assertInstanceOf(config('newsletter.model'), $subscriber = $this->getSubscriber($email));
        $this->assertNull($subscriber->verified_at);
    }

    public function test_subscribe_json_dup()
    {
        $this->createSubscriber([
            'email' => $email = 'john@smith.com',
        ]);

        $this->json('post', '/subscribe', [
            'email' => $email,
        ])->assertCreated();
    }

    public function test_subscribe_json_validation()
    {
        $this->json('post', '/subscribe', [
            'email' => 'foo',
        ])->assertJsonValidationErrors([
            'email',
        ]);
    }

    public function test_subscribe()
    {
        $email = 'john@smith.com';
        $this
            ->post('/subscribe', [
                'email' => $email,
            ])
            ->assertRedirect('/')
            ->assertSessionHas('newsletter.message', __('newsletter::messages.subscribed'));

        $this->assertInstanceOf(config('newsletter.model'), $this->getSubscriber($email));
    }

    public function test_subscribe_validation()
    {
        $this
            ->post('/subscribe', [
                'email' => 'foo',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors([
                'email',
            ], null, 'newsletter');
    }

    public function test_subscribe_event_listener()
    {
        Event::fake([
            Subscribed::class, // Specify, otherwise breaks \Illuminate\Database\Eloquent\Concerns\HasUlids::bootHasUlids
        ]);
        Event::assertListening(
            Subscribed::class,
            SendSubscribedNotification::class
        );

        $email = 'john@smith.com';
        $this->json('post', '/subscribe', [
            'email' => $email,
        ]);

        Event::assertDispatched(function (Subscribed $event) use ($email) {
            $subscriberModel = config('newsletter.model');

            return $event->subscriber instanceof $subscriberModel && $event->subscriber->email === $email;
        });
    }

    public function test_subscribe_event_listener_foo()
    {
        Event::fake([
            Subscribed::class,
        ]);
        $this->createSubscriber([
            'email' => $email = 'john@smith.com',
        ]);

        $this->json('post', '/subscribe', [
            'email' => $email,
        ]);

        Event::assertNotDispatched(Subscribed::class);
    }

    public function test_subscribe_notification()
    {
        Notification::fake();

        $email = 'john@smith.com';
        $this->json('post', '/subscribe', [
            'email' => $email,
        ]);

        Notification::assertSentOnDemand(
            \MIBU\Newsletter\Notifications\SubscribedNotification::class,
            function (
                \MIBU\Newsletter\Notifications\SubscribedNotification $notification,
                array                                                 $channels,
                object                                                $notifiable,
            ) use ($email) {
                return $notifiable->routes['mail'] === $email;
            }
        );
    }

    /*
    public function test_mail_send()
    {
        Mail::fake();

        $email = 'john@smith.com';
        $this->json('post', '/subscribe', [
            'email' => $email,
        ]);

        Mail::assertSent(
            \MIBU\Newsletter\Mail\Foo::class,
            function (\MIBU\Newsletter\Mail\Foo $mail) use ($email) {
                return $mail->hasTo($email);
            },
        );
    }
    */

    public function test_mail_content()
    {
        $subscriber = $this->createSubscriber();
        $mailable = new \MIBU\Newsletter\Mail\Subscribed($subscriber);

        $verifyUrl = URL::signedRoute('newsletter.verify', [
            'id' => $subscriber->getKey(),
        ]);
        $mailable->assertSeeInHtml($verifyUrl);

        $unsubscribeUrl = URL::signedRoute('newsletter.unsubscribe', [
            'id' => $subscriber->getKey(),
        ]);
        $mailable->assertSeeInHtml($unsubscribeUrl);
    }
}
