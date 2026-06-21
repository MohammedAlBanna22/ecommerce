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

    public function add(Product $product)
    {
        try {
            $this->cartService->add($product, auth()->user());

            return back()->with('success', 'Added to cart');

        } catch (\Exception $e) {
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
