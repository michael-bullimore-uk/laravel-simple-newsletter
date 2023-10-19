<?php

use App\Actions\Newsletter\Subscribe;
use App\Actions\Newsletter\Unsubscribe;
use App\Actions\Newsletter\Verify;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Facades\Route;
use MIBU\Newsletter\Contracts\SubscribeResponse;
use MIBU\Newsletter\Contracts\UnsubscribedResponse;
use MIBU\Newsletter\Contracts\VerifiedResponse;

Route::post('/subscribe', function (Request $request) {
    app(Subscribe::class)->exec($request->all());

    return app(SubscribeResponse::class)->toResponse($request);
})->name('subscribe');

Route::get('/verify/{id}', function (string $id, Request $request) {
    if (! $request->hasValidSignature()) {
        throw new InvalidSignatureException();
    }

    app(Verify::class)->exec($id);

    return app(VerifiedResponse::class)->toResponse($request);
})->name('verify');

Route::get('/unsubscribe/{id}', function (string $id, Request $request) {
    if (! $request->hasValidSignature()) {
        throw new InvalidSignatureException();
    }

    app(Unsubscribe::class)->exec($id);

    return app(UnsubscribedResponse::class)->toResponse($request);
})->name('unsubscribe');
