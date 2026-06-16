<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;


Route::middleware('auth')
->group(function(){


    Route::get('/checkout',
        [CheckoutController::class,'index']
    )
    ->name('checkout.index');


    Route::post('/checkout',
        [CheckoutController::class,'store']
    )
    ->name('checkout.store');


});