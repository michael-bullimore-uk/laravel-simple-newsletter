<?php

namespace App\Actions\Newsletter;

use MIBU\Newsletter\Events\Unsubscribed;
use MIBU\Newsletter\Models\Subscriber;

class Unsubscribe
{
    public function exec(string $id): bool|null
    {
        /** @var Subscriber $subscriber */
        $subscriber = app(config('newsletter.model'))->findOrFail($id);

        $result = $subscriber->delete();
        if ($result) {
            event(new Unsubscribed($subscriber));
        }

        return $result;
    }
}
