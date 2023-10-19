<?php

use App\Actions\Newsletter\Subscribe;
use App\Actions\Newsletter\Unsubscribe;
use App\Actions\Newsletter\Verify;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::post('/subscribe', function (Request $request) {
    app(Subscribe::class)->exec($request->all());

    if ($request->expectsJson()) {
        return response()->json([], Response::HTTP_CREATED);
    }

    // back()
    return redirect('/')->with('newsletter.message', __('newsletter::messages.subscribed'));
})->name('subscribe');

Route::get('/verify/{id}', function (string $id, Request $request) {
    if (! $request->hasValidSignature()) {
        throw new InvalidSignatureException();
    }

    app(Verify::class)->exec($id);

    if ($request->expectsJson()) {
        return response()->noContent();
    }

    return redirect('/')->with('newsletter.message', __('newsletter::messages.verified'));
})->name('verify');

Route::get('/unsubscribe/{id}', function (string $id, Request $request) {
    if (! $request->hasValidSignature()) {
        throw new InvalidSignatureException();
    }

    app(Unsubscribe::class)->exec($id);

    if ($request->expectsJson()) {
        return response()->noContent();
    }

    return redirect('/')->with('newsletter.message', __('newsletter::messages.unsubscribed'));
})->name('unsubscribe');
