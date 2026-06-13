<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function(){


    Route::get('/cart',
    [CartController::class,'index'])
    ->name('cart.index');


    Route::post('/cart/add/{product}',
    [CartController::class,'add'])
    ->name('cart.add');


    Route::put('/cart/item/{item}',
    [CartController::class,'update'])
    ->name('cart.update');


    Route::delete('/cart/item/{item}',
    [CartController::class,'destroy'])
    ->name('cart.destroy');


});

require __DIR__.'/auth.php';
