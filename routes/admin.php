<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;




Route::middleware(['auth','admin'])
->prefix('admin')
->group(function(){


    Route::get('/orders',
    [OrderController::class,'index'])
    ->name('admin.orders.index');


    Route::put('/orders/{order}/status',
    [OrderController::class, 'updateStatus'])
    ->name('admin.orders.updateStatus');

     Route::get('/dashboard',
        [DashboardController::class,'index']
    )->name('admin.dashboard');


     Route::get('/orders/{order}',
        [AdminOrderController::class,'show'])
        ->name('admin.orders.show');
    Route::get('/orders/{order}/invoice',
        [AdminOrderController::class,'downloadInvoice'])
        ->name('admin.orders.invoice');


    Route::patch(
    '/admin/orders/{order}/status',
    [OrderController::class,'updateStatus']
    )
    ->name('orders.status');




});
