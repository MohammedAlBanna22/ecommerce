<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(
        private CartService $cartService
    ) {}

    public function index()
    {
        $cart = Cart::with('items.product')
            ->where('user_id', auth()->id())
            ->first();

        $total = $cart?->total ?? 0;

        return view('cart.index', compact('cart', 'total'));
    }

public function add(Request $request, Product $product)
{
    try {
        $this->cartService->add($product, auth()->user());

        // حساب عدد الكارت بشكل صحيح
        $cartCount = 0;

        if (auth()->check()) {
            $cart = auth()->user()->cart;

            if ($cart) {
                $cartCount = $cart->items()->sum('quantity');
            }
        } else {
            $cartCount = collect(session('cart', []))->sum('quantity');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'cartCount' => $cartCount,
            ]);
        }

        return back()->with('success', 'Added to cart');

    } catch (\Exception $e) {

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return back()->with('error', $e->getMessage());
    }
}

    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $this->cartService->update($item, $request->quantity);

            return back()->with('success', 'Cart updated');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(CartItem $item)
    {
        abort_unless($item->cart->user_id === auth()->id(), 403);

        try {
            $this->cartService->remove($item);

            return back()->with('success', 'Removed from cart');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
