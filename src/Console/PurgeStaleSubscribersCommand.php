<?php

namespace MIBU\Newsletter\Console;

use Illuminate\Console\Command;

class PurgeStaleSubscribersCommand extends Command
{
    /** @var string */
    protected $signature = 'newsletter:purge-stale-subscribers {days?}';

    /** @var string */
    protected $description = 'Purge stale subscribers.';

    public function handle(): void
    {
        $days = $this->argument('days') ?? config('newsletter.purge_stale_subscribers_days');
        app(config('newsletter.model'))->stale($days)->delete();
    }
}
