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
        [
            $subscriber,
            $plainTextToken,
        ] = $this->createSubscriber();
        $this->assertNull($subscriber->verified_at);

        $this->json('get', "/verify/{$subscriber->id}/{$plainTextToken}")->assertNoContent();

        $subscriber->refresh();
        $this->assertInstanceOf(DateTime::class, $subscriber->verified_at);
    }

    public function test_verify_json_invalid()
    {
        [
            $subscriber,
            $plainTextToken,
        ] = $this->createSubscriber();
        $this->json('get', "/verify/0/{$plainTextToken}")->assertNotFound();
        $this->json('get', "/verify/{$subscriber->id}/foo")->assertStatus(Response::HTTP_I_AM_A_TEAPOT);
    }

    public function test_verify()
    {
        [
            $subscriber,
            $plainTextToken,
        ] = $this->createSubscriber();
        $this
            ->get("/verify/{$subscriber->id}/{$plainTextToken}")
            ->assertRedirect()
            ->assertSessionHas('newsletter.message');
        ;

        $subscriber->refresh();
        $this->assertInstanceOf(DateTime::class, $subscriber->verified_at);
    }
}
