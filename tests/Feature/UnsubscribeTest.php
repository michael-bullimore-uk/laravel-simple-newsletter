<?php

namespace MIBU\Newsletter\Tests\Feature;

use MIBU\Newsletter\Factories\SubscriberFactory;
use MIBU\Newsletter\Tests\TestCase;

class UnsubscribeTest extends TestCase
{
    public function test_unsubscribe_json()
    {
        $subscriber = (new SubscriberFactory())->create();
        $this->json('get', "/unsubscribe/{$subscriber->id}/{$subscriber->token}")->assertNoContent();

        $this->assertModelMissing($subscriber);
    }

    public function test_unsubscribe()
    {
        $subscriber = (new SubscriberFactory())->create();
        $this->get("/unsubscribe/{$subscriber->id}/{$subscriber->token}")->assertRedirect();

        $this->assertModelMissing($subscriber);
    }
}
