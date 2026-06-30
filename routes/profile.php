<?php

use Illuminate\Support\Facades\Route;


/**
 * ═════════════════════════════════════════════════════════════════════════
 * PROFILE ROUTES
 *
 * Add these to your routes/web.php inside a Route::middleware(['auth']) group
 * ═════════════════════════════════════════════════════════════════════════
 */

Route::middleware(['auth'])->group(function () {

    /**
     * Profile Management
     */
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'avatar'])
        ->name('profile.avatar');

    Route::post('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'uploadAvatar'])
        ->name('profile.avatar.upload');

    /**
     * Password Management
     */
    Route::get('/profile/security', [\App\Http\Controllers\ProfileController::class, 'security'])
        ->name('profile.security');

    Route::put('/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('password.update');

    /**
     * Notifications
     */
    Route::get('/profile/notifications', [\App\Http\Controllers\ProfileController::class, 'notifications'])
        ->name('profile.notifications');

    /**
     * Account Deletion
     */
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /**
     * ═════════════════════════════════════════════════════════════════════════
     * ADDRESS MANAGEMENT (Related to Profile)
     * ═════════════════════════════════════════════════════════════════════════
     */
    Route::resource('addresses', \App\Http\Controllers\AddressController::class);

    /**
     * ═════════════════════════════════════════════════════════════════════════
     * PAYMENT METHODS (Related to Profile)
     * ═════════════════════════════════════════════════════════════════════════
     */
    Route::resource('payment-methods', \App\Http\Controllers\PaymentMethodController::class)
        ->only(['index', 'show', 'create', 'store', 'destroy']);

    /**
     * ═════════════════════════════════════════════════════════════════════════
     * REVIEWS (Related to Profile)
     * ═════════════════════════════════════════════════════════════════════════
     */
    Route::resource('reviews', \App\Http\Controllers\ReviewController::class)
        ->only(['index', 'show', 'edit', 'update', 'destroy']);

});