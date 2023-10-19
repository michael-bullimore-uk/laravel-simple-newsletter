<?php

namespace MIBU\Newsletter\Tests\Feature;

use DateTime;
use MIBU\Newsletter\Tests\TestCase;

class VerifyTest extends TestCase
{
    public function test_verify_json()
    {
        $subscriber = $this->createSubscriber();
        $this->assertNull($subscriber->verified_at);

        $this->json('get', $subscriber->getVerifyUrl())->assertNoContent();

        $subscriber->refresh();
        $this->assertInstanceOf(DateTime::class, $subscriber->verified_at);
    }

    public function test_verify_json_invalid()
    {
        $subscriber = $this->createSubscriber();

        $this->json('get', "/verify/{$subscriber->id}")->assertForbidden();
    }

    public function test_verify()
    {
        $subscriber = $this->createSubscriber();
        $this
            ->get($subscriber->getVerifyUrl())
            ->assertRedirect()
            ->assertSessionHas('newsletter.message', __('newsletter::messages.verified'));
        ;

        $subscriber->refresh();
        $this->assertInstanceOf(DateTime::class, $subscriber->verified_at);
    }

    public function test_verify_invalid()
    {
        $subscriber = $this->createSubscriber();

        $this->get("/verify/{$subscriber->id}")->assertForbidden();
    }
}
