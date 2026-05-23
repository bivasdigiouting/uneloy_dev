<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/user.php'));

            Route::middleware('web')
                ->group(base_path('routes/vendor.php'));

            Route::middleware('web')
                ->group(base_path('routes/ecardseva.php'));

            Route::prefix('api/v1')
                ->middleware('api')
                ->group(base_path('routes/api_v1.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn (Request $request) => match (true) {
            $request->is('admin/*') => route('admin.login'),
            $request->is('ecard/*') => route('ecard.login'),
            default => route('user.login'),
        });

        $middleware->web(append: [
            \App\Http\Middleware\MaintenanceMode::class,
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'ecard.permissions' => \App\Http\Middleware\ECardPermissionMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'ecard/registration/payment/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
