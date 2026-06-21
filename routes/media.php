<?php

use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;






Route::post('/media/{media}/set-main', [MediaController::class, 'setMain']);
Route::delete('/media/{media}', [MediaController::class, 'destroy']);
Route::post('/media/order',
    [MediaController::class, 'order']
)->name('media.order');
