<?php

namespace MIBU\Newsletter\Http\Responses;

use MIBU\Newsletter\Contracts\UnsubscribedResponse as UnsubscribedResponseContract;

class UnsubscribedResponse implements UnsubscribedResponseContract
{
    public function toResponse($request)
    {
        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return redirect('/')->with('newsletter.message', __('newsletter::messages.unsubscribed'));
    }
}
