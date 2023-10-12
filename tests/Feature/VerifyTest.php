<?php

namespace MIBU\Newsletter\Tests\Feature;

use DateTime;
use MIBU\Newsletter\Factories\SubscriberFactory;
use MIBU\Newsletter\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class VerifyTest extends TestCase
{
    public function test_verify_json()
    {
        $subscriber = (new SubscriberFactory())->create();
        $this->assertNull($subscriber->verified_at);

        $this->json('get', "/verify/{$subscriber->id}/{$subscriber->token}")->assertNoContent();

        $subscriber->refresh();
        $this->assertInstanceOf(DateTime::class, $subscriber->verified_at);
    }

    public function test_verify_json_invalid()
    {
        $subscriber = (new SubscriberFactory())->create();
        $this->json('get', "/verify/0/{$subscriber->token}")->assertNotFound();
        $this->json('get', "/verify/{$subscriber->id}/foo")->assertStatus(Response::HTTP_I_AM_A_TEAPOT);
    }

    public function test_verify()
    {
        $subscriber = (new SubscriberFactory())->create();
        $this->get("/verify/{$subscriber->id}/{$subscriber->token}")->assertRedirect();

        $subscriber->refresh();
        $this->assertInstanceOf(DateTime::class, $subscriber->verified_at);
    }
}
