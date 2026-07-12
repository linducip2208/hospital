<?php

use App\Http\Middleware\CheckPoliAccess;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\RequirePair;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', RequirePair::class);

        $middleware->alias([
            'role' => CheckRole::class,
            'poli.access' => CheckPoliAccess::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('portal', 'portal/*')) {
                return route('portal.login');
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
