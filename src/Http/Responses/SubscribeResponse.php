<?php

namespace MIBU\Newsletter\Http\Responses;

use MIBU\Newsletter\Contracts\SubscribeResponse as SubscribeResponseContract;
use Symfony\Component\HttpFoundation\Response;

class SubscribeResponse implements SubscribeResponseContract
{
    public function toResponse($request)
    {
        if ($request->expectsJson()) {
            return response()->json([], Response::HTTP_CREATED);
        }

        return back()->with('newsletter.message', __('newsletter::messages.subscribed'));
    }
}
