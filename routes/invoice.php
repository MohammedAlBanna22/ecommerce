<?php
use App\Http\Controllers\InvoiceController;

use Illuminate\Support\Facades\Route;



Route::get(
'/orders/{order}/invoice',
[InvoiceController::class,'download']
)
->name('orders.invoice')
//->middleware('auth')
;