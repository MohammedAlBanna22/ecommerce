<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
         then: function () {


            Route::middleware('web')
                ->group(base_path('routes/cart.php'));


            Route::middleware('web')
                ->group(base_path('routes/checkout.php'));

            Route::middleware('web')
                ->group(base_path('routes/orders.php'));

            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

             Route::middleware('web')
                ->group(base_path('routes/invoice.php'));

            Route::middleware('web')
                ->group(base_path('routes/media.php'));
             Route::middleware('web')
                ->group(base_path('routes/profile.php'));


        },
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
    'admin' => AdminMiddleware::class,
    ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
