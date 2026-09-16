<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(
            prepend: [
                \App\Http\Middleware\HandleETag::class,
            ],
            append: [
                \App\Http\Middleware\SetLocale::class,
                \App\Http\Middleware\HandleInertiaRequests::class,
                \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            ],
        );

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Expired CSRF token: for Inertia form submits we redirect back so
        // the Toaster can pick up the flash error and explain what happened.
        // Direct browser hits fall through to resources/views/errors/419.blade.php.
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->header('X-Inertia')) {
                return back()->with(
                    'error',
                    "Ta session a expiré. Reconnecte-toi puis réessaie."
                );
            }

            return null;
        });
    })->create();
