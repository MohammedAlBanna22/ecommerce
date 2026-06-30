<?php

use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;






Route::delete('/media/{media}', [MediaController::class, 'destroy'])
->name('media.destroy');
 
Route::post('/media/{media}/set-main', [MediaController::class, 'setMain'])
    ->name('media.set-main');
 
Route::post('/media/order', [MediaController::class, 'order'])
    ->name('media.order');