<?php

namespace App\Actions\Newsletter;

use MIBU\Newsletter\Models\Subscriber;

class Verify
{
    public function exec(int $id, string $token): Subscriber
    {
        $subscriber = app(config('newsletter.model'))->findOrFail($id);

        if ($subscriber->token !== $token) {
            abort(418);
        }

        if (! $subscriber->verified_at) {
            $subscriber->update([
                'verified_at' => now(),
            ]);
        }

        return $subscriber;
    }
}
