<?php

namespace MIBU\Newsletter\Http\Responses;

use MIBU\Newsletter\Contracts\VerifiedResponse as VerifiedResponseContract;

class VerifiedResponse implements VerifiedResponseContract
{
    public function toResponse($request)
    {
        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return redirect('/')->with('newsletter.message', __('newsletter::messages.verified'));
    }
}
