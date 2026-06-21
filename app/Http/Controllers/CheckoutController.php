<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    //
    public function index()
    {

    $cart = Cart::with('items.product')
        ->where('user_id',auth()->id())
        ->first();


    return view('checkout.index',compact('cart'));

    }

    public function store(Request $request, OrderService $orderService)
    {
        $cart = Cart::with('items.product')
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty');
        }

        try {

            $order = $orderService->createOrder(
                auth()->user(),
                $cart,
                $request
            );

            $order->histories()->create([
                'status' => 'pending'
            ]);

            // notifications (تبقى في controller لأنها "side effect")
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                $admin->notify(new NewOrderNotification($order));
            }



            // clear cart
            $cart->items()->delete();

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Order created successfully');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
