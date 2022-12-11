<?php

namespace App\Actions\Newsletter;

class Unsubscribe
{
    public function exec(int $id, string $token): bool|null
    {
        $subscriber = app(config('newsletter.model'))->findOrFail($id);

        if ($subscriber->token !== $token) {
            abort(418);
        }

        return $subscriber->delete();
    }
}
