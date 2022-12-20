<?php

namespace MIBU\Newsletter\Tests\Feature;

use MIBU\Newsletter\Tests\TestCase;

class SubscribeTest extends TestCase
{
    public function test_subscribe_json()
    {
        $email = 'john@smith.com';
        $response = $this->json('post','/subscribe', [
            'email' => $email,
        ]);

        $response->assertExactJson([]);
        $response->assertCreated();

        $this->assertInstanceOf(config('newsletter.model'), $this->getSubscriber($email));
    }

    public function test_subscribe_json_validation()
    {
        $data = [
            'email' => 'john@smith.com',
        ];
        $this->json('post','/subscribe', $data)->assertCreated();

        $response = $this->json('post','/subscribe', $data);
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
}
