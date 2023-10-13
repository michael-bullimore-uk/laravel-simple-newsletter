<?php

namespace App\Actions\Newsletter;

use Illuminate\Support\Facades\Hash;
use MIBU\Newsletter\Events\SubscriberVerified;
use MIBU\Newsletter\Models\Subscriber;

class Verify
{
    public function exec(string $id, string $token): Subscriber
    {
        $subscriber = app(config('newsletter.model'))->findOrFail($id);

        if (! $subscriber->isValidToken($token)) {
            abort(418);
        }

        if (! $subscriber->verified_at) {
            $subscriber->update([
                'verified_at' => now(),
            ]);
            event(new SubscriberVerified($subscriber));
        }

        return $subscriber;
    }
}
