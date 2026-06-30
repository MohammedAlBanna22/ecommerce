<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
     Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);


Route::middleware('auth')->group(function () {
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::post('/addresses/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
      Route::get('/home', [HomeController::class,'index'])
    ->name('home');

    // ── Notifications ──
    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.markAllRead');

    // لو تريد تمييز إشعار واحد:
    Route::post('/notifications/{id}/read', function ($id) {
        $notif = auth()->user()->notifications()->find($id);
        if ($notif) $notif->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.markRead');

});
 // --- PAYMENT (Page 2 - Only for Cards!) ---
    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{order}/process', [PaymentController::class, 'processStripe'])->name('payment.process');
    Route::get('/payment/{order}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{order}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

    // --- Alternative Payments ---
    Route::post('/payment/{order}/cod', [PaymentController::class, 'processCOD'])->name('payment.cod');
    Route::post('/payment/{order}/paypal', [PaymentController::class, 'processPayPal'])->name('payment.paypal');
      // معالجة دفع Stripe
    Route::post('/order/{order}/pay/stripe', [PaymentController::class, 'processStripe'])
        ->name('payment.stripe');

    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->name('stripe.webhook');





require __DIR__.'/auth.php';
