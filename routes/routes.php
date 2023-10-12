<?php

use App\Actions\Newsletter\Subscribe;
use App\Actions\Newsletter\Unsubscribe;
use App\Actions\Newsletter\Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Customise the response(s) to your requirements.
 */

Route::post('/subscribe', function (Request $request) {
    app(Subscribe::class)->exec($request->all());

    if ($request->expectsJson()) {
        return response()->json([], Response::HTTP_CREATED);
    }

    return redirect('/');
})->name('subscribe');

Route::get('/verify/{id}/{token}', function (int $id, string $token, Request $request) {
    app(Verify::class)->exec($id, $token);

    if ($request->expectsJson()) {
        return response()->noContent();
    }

    return redirect('/');
})->name('verify');

Route::get('/unsubscribe/{hashId}/{token}', function (int $id, string $token, Request $request) {
    app(Unsubscribe::class)->exec($id, $token);

    if ($request->expectsJson()) {
        return response()->noContent();
    }

    return redirect('/');
})->name('unsubscribe');
