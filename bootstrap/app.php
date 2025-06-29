<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/master-admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // تسجيل middleware مخصص
        $middleware->alias([
            'admin.access' => \App\Http\Middleware\AdminAccessMiddleware::class,
            'master.admin' => \App\Http\Middleware\MasterAdminMiddleware::class,
            'check.license' => \App\Http\Middleware\CheckLicense::class,
            'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'data_isolation' => \App\Http\Middleware\LicenseDataIsolationMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
