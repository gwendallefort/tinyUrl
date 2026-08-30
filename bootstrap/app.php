<?php

use App\Http\Middleware\SetLocale;
use App\Http\Middleware\StagingAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: [
            SetLocale::COOKIE,
        ]);

        $middleware->web(append: [
            SetLocale::class,
            StagingAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (InvalidSignatureException $e, $request) {
            if ($request->routeIs('verification.verify')) {
                return redirect()
                    ->route('dashboard')
                    ->with('status', 'verification-link-expired');
            }
        });
    })->create();
