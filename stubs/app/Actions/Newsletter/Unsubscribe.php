<?php

namespace App\Actions\Newsletter;

use MIBU\Newsletter\Events\Subscribed;
use MIBU\Newsletter\Events\Unsubscribed;
use MIBU\Newsletter\Models\Subscriber;

class Unsubscribe
{
    public function exec(int $id, string $token): bool|null
    {
        /** @var Subscriber $subscriber */
        $subscriber = app(config('newsletter.model'))->findOrFail($id);

        if ($subscriber->token !== $token) {
            abort(418);
        }

        $result = $subscriber->delete();
        if ($result) {
            event(new Unsubscribed($subscriber));
        }

        return $result;
    }
}
