<?php

namespace App\Actions\Newsletter;

use Illuminate\Support\Facades\Hash;
use MIBU\Newsletter\Events\SubscriberVerified;
use MIBU\Newsletter\Models\Subscriber;

class Verify
{
    public function exec(int $id, string $token): Subscriber
    {
        $subscriber = app(config('newsletter.model'))->findOrFail($id);

        // Hash::check($token, $subscriber->token);
        if ($subscriber->token !== $token) {
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
