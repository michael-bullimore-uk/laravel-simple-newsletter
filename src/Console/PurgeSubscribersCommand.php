<?php

namespace MIBU\Newsletter\Console;

use Illuminate\Console\Command;

class PurgeSubscribersCommand extends Command
{
    /** @var string */
    protected $signature = 'newsletter:purge-subscribers';

    /** @var string */
    protected $description = 'Purge subscribers.';

    public function handle(): void
    {
        $days = config('newsletter.purge_subscribers_days');
        app(config('newsletter.model'))
            ->whereNull('verified_at')
            ->where('created_at', '<=', now()->modify("-{$days} days"))
            ->delete();
    }
}
