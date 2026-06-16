<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;


Route::middleware('auth')
->group(function(){


    Route::get('/cart',
        [CartController::class,'index']
    )
    ->name('cart.index');


    Route::post('/cart/add/{product}',
        [CartController::class,'add']
    )
    ->name('cart.add');


    Route::put('/cart/item/{item}',
        [CartController::class,'update']
    )
    ->name('cart.update');


   Route::delete('/cart/item/{item}', [CartController::class, 'destroy'])->name('cart.destroy');


});