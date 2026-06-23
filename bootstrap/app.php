<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies (Cloudflare Tunnel / Render)
        $middleware->trustProxies(at: '*');

        // Exclude customer-facing routes from CSRF so pelanggan tidak kena "419 Page Expired"
        $middleware->validateCsrfTokens(except: [
            'keranjang/*',
            'keranjang',
            'checkout/*',
            'tracking/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
