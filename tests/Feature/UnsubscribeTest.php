<?php

namespace MIBU\Newsletter\Tests\Feature;

use Illuminate\Routing\Exceptions\InvalidSignatureException;
use MIBU\Newsletter\Tests\TestCase;

class UnsubscribeTest extends TestCase
{
    public function test_unsubscribe_json()
    {
        $subscriber = $this->createSubscriber();
        $this->json('get', $subscriber->getUnsubscribeUrl())->assertNoContent();

        $this->assertModelMissing($subscriber);
    }

    /*
    public function test_unsubscribe_json_invalid()
    {
        $subscriber = $this->createSubscriber();

        $this->expectException(InvalidSignatureException::class);
        $this->withoutExceptionHandling()->json('get', "/unsubscribe/{$subscriber->id}");
    }
    */

    public function test_unsubscribe_json_invalid()
    {
        $subscriber = $this->createSubscriber();
        $this->json('get', "/unsubscribe/{$subscriber->id}")->assertForbidden();
    }

    public function test_unsubscribe_json_missing()
    {
        $subscriber = $this->createSubscriber();
        $unsubscribeUrl = $subscriber->getUnsubscribeUrl();
        $subscriber->delete();

        $this->json('get', $unsubscribeUrl)->assertNotFound();
    }

    public function test_unsubscribe()
    {
        $subscriber = $this->createSubscriber();
        $this
            ->get($subscriber->getUnsubscribeUrl())
            ->assertRedirect()
            ->assertSessionHas('newsletter.message', __('newsletter::messages.unsubscribed'));

        $this->assertModelMissing($subscriber);
    }

    public function test_unsubscribe_invalid()
    {
        $subscriber = $this->createSubscriber();

        $this->get("/unsubscribe/{$subscriber->id}")->assertForbidden();
    }
}
