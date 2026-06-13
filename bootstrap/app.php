<?php

use App\Http\Middleware\Admin;
use App\Http\Middleware\Faculty;
use App\Http\Middleware\HandleSessionExpiration;
use App\Http\Middleware\LogUserAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            LogUserAccess::class,
        ]);

        //admin middleware
        $middleware->alias([
            'admin' => Admin::class,
        ]);
        //faculty middleware
        $middleware->alias([
            'faculty' => Faculty::class,
        ]);
        //session expiration middleware
        $middleware->alias([
            'session.expiration' => HandleSessionExpiration::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
