<?php

namespace App\Actions\Newsletter;

use Illuminate\Support\Facades\Hash;
use MIBU\Newsletter\Events\Subscribed;
use MIBU\Newsletter\Events\Unsubscribed;
use MIBU\Newsletter\Models\Subscriber;

class Unsubscribe
{
    public function exec(string $id, string $token): bool|null
    {
        /** @var Subscriber $subscriber */
        $subscriber = app(config('newsletter.model'))->findOrFail($id);

        if (! $subscriber->isValidToken($token)) {
            abort(418);
        }

        $result = $subscriber->delete();
        if ($result) {
            event(new Unsubscribed($subscriber));
        }

        return $result;
    }
}
