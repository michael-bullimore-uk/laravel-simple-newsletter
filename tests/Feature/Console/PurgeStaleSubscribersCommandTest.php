<?php

namespace MIBU\Newsletter\Tests\Feature\Console;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use MIBU\Newsletter\Console\PurgeStaleSubscribersCommand;
use MIBU\Newsletter\Models\Subscriber;
use MIBU\Newsletter\Tests\TestCase;

class PurgeStaleSubscribersCommandTest extends TestCase
{
    public function test_purge_subscribers_command_exited()
    {
        $this->artisan('newsletter:purge-stale-subscribers')->assertExitCode(0);
    }

    public function test_purge_subscribers()
    {
        $now = now();
        $purgeSubscriberDays = config('newsletter.purge_stale_subscribers_days');

        $this->createSubscriber(); // Unverified
        $this->createSubscriber([ // Verified
            'verified_at' => $now,
        ]);
        $staleUnverifiedSubscriber = $this->createSubscriber([ // Stale unverified
            'created_at' => $now->subDays($purgeSubscriberDays)->subSecond(),
        ]);

        $this->artisan(PurgeStaleSubscribersCommand::class);
        $this->assertEquals(2, Subscriber::count());

        $this->expectException(ModelNotFoundException::class);
        $staleUnverifiedSubscriber->refresh();
    }

    public function test_purge_subscribers_days()
    {
        $unverifiedSubscriber = $this->createSubscriber([
            'created_at' => now()->subWeek()->subSecond(),
        ]);

        $this->artisan(PurgeStaleSubscribersCommand::class, [
            'days' => 7,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $unverifiedSubscriber->refresh();
    }
}
