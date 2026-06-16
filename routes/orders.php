<?php

use App\Http\Controllers\OrderController;

use Illuminate\Support\Facades\Route;



Route::middleware('auth')->group(function(){


Route::get('/orders',
[OrderController::class,'index'])
->name('orders.index');


Route::get('/orders/{order}',
[OrderController::class,'show'])
->name('orders.show');


});
