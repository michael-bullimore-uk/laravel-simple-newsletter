<?php

namespace MIBU\Newsletter\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use MIBU\Newsletter\Events\Subscribed;
use MIBU\Newsletter\Factories\SubscriberFactory;
use MIBU\Newsletter\Listeners\Foo;
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
        $this->assertNotNull($subscriber->token);
        $this->assertNull($subscriber->verified_at);
    }

    public function test_subscribe_json_validation()
    {
        $data = [
            'email' => 'john@smith.com',
        ];
        $this->json('post', '/subscribe', $data)->assertCreated();

        $response = $this->json('post', '/subscribe', $data);
        $response->assertJsonValidationErrors([
            'email',
        ]);
    }

    public function test_subscribe()
    {
        $email = 'john@smith.com';
        $response = $this->post('/subscribe', [
            'email' => $email,
        ]);
        $response->assertRedirect('/');

        $this->assertInstanceOf(config('newsletter.model'), $this->getSubscriber($email));
    }

    public function test_subscribe_validation()
    {
        $data = [
            'email' => 'john@smith.com',
        ];
        $this->post('/subscribe', $data)->assertRedirect();

        $response = $this->post('/subscribe', $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
           'email',
        ], null, 'newsletter');
    }

    public function test_subscribe_event_listener()
    {
        Event::fake();

        $email = 'john@smith.com';
        $this->json('post', '/subscribe', [
            'email' => $email,
        ]);

        Event::assertDispatched(function (Subscribed $event) use ($email) {
            $subscriberModel = config('newsletter.model');

            return $event->subscriber instanceof $subscriberModel && $event->subscriber->email === $email;
        });
        Event::assertListening(
            Subscribed::class,
            Foo::class
        );
    }

    public function test_subscribe_notification()
    {
        Notification::fake();

        $email = 'john@smith.com';
        $this->json('post', '/subscribe', [
            'email' => $email,
        ]);

        Notification::assertSentOnDemand(
            \MIBU\Newsletter\Notifications\Foo::class,
            function (
                \MIBU\Newsletter\Notifications\Foo $notification,
                array $channels,
                object $notifiable,
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
        $subscriber = (new SubscriberFactory())->create();

        $mailable = new \MIBU\Newsletter\Mail\Foo($subscriber);
        $mailable->assertSeeInHtml($subscriber->token);
    }
}
