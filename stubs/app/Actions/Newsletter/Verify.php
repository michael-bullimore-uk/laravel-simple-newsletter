<?php

namespace App\Actions\Newsletter;

use MIBU\Newsletter\Events\SubscriberVerified;
use MIBU\Newsletter\Models\Subscriber;

class Verify
{
    public function exec(string $id): Subscriber
    {
        $subscriber = app(config('newsletter.model'))->findOrFail($id);

        if (! $subscriber->verified_at) {
            $subscriber->update([
                'verified_at' => now(),
            ]);

            event(new SubscriberVerified($subscriber));
        }

        return $subscriber;
    }
}
