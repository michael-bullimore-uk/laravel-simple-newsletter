<?php

namespace MIBU\Newsletter\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MIBU\Newsletter\Factories\SubscriberFactory;
use MIBU\Newsletter\Tests\TestCase;

class UnsubscribeTest extends TestCase
{
    public function test_unsubscribe_json()
    {
        [
            $subscriber,
            $plainTextToken,
        ] = $this->createSubscriber();
        $this->json('get', "/unsubscribe/{$subscriber->id}/{$plainTextToken}")->assertNoContent();

        $this->assertModelMissing($subscriber);
    }

    public function test_unsubscribe()
    {
        [
            $subscriber,
            $plainTextToken,
        ] = $this->createSubscriber();
        $this
            ->get("/unsubscribe/{$subscriber->id}/{$plainTextToken}")
            ->assertRedirect()
            ->assertSessionHas('newsletter.message');

        $this->assertModelMissing($subscriber);
    }
}
