<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Show the application homepage - Amazon style
     */
    public function index(): View
    {
        // ╔════════════════════════════════════════════════════╗
        // ║ FEATURED PRODUCT (Random High-Quality)             ║
        // ╚════════════════════════════════════════════════════╝
        $featuredProduct = Cache::remember('home_featured_product', 3600, function () {
            return Product::query()
                ->where('status', 'available')
                ->with('mainImage')
                ->inRandomOrder()
                ->first();
        });

        // ╔════════════════════════════════════════════════════╗
        // ║ CATEGORIES (with product count)                    ║
        // ╚════════════════════════════════════════════════════╝
        $categories = Cache::remember('home_categories', 3600, function () {
            return Category::query()
                ->withCount('products')
                ->orderBy('name')
                ->limit(12)
                ->get();
        });

        // ╔════════════════════════════════════════════════════╗
        // ║ TODAY'S DEALS (Limited Time Offers)                ║
        // ╚════════════════════════════════════════════════════╝
        $dealProducts = Cache::remember('home_deal_products', 1800, function () {
            return Product::query()
                ->where('status', 'available')
                ->whereNotNull('discount_price')
                ->where('discount_price', '>', 0)
                ->with('mainImage')
                ->orderByRaw('(discount_price - price) / discount_price DESC') // Highest discount %
                ->limit(20)
                ->get();
        });

        // ╔════════════════════════════════════════════════════╗
        // ║ NEW ARRIVALS (Recently Added)                      ║
        // ╚════════════════════════════════════════════════════╝
        $newProducts = Cache::remember('home_new_products', 3600, function () {
            return Product::query()
                ->where('status', 'available')
                ->with('mainImage')
                ->orderByDesc('created_at')
                ->limit(12)
                ->get();
        });

        // ╔════════════════════════════════════════════════════╗
        // ║ BEST SELLERS (Most Sold / Low Stock)               ║
        // ╚════════════════════════════════════════════════════╝
        $bestSellers = Cache::remember('home_best_sellers', 1800, function () {
            return Product::query()
                ->where('status', 'available')
                ->with('mainImage')
                ->orderByRaw('CAST(quantity - reserved_quantity AS SIGNED) ASC') // Low stock = best sellers
                ->whereRaw('(quantity - reserved_quantity) > 0') // Only in stock items
                ->limit(4)
                ->get();
        });

        // ╔════════════════════════════════════════════════════╗
        // ║ TRENDING NOW (Hot Items - Recently Updated)        ║
        // ╚════════════════════════════════════════════════════╝
        $trendingProducts = Cache::remember('home_trending', 3600, function () {
            return Product::query()
                ->where('status', 'available')
                ->with('mainImage')
                ->orderByDesc('updated_at')
                ->whereRaw('(quantity - reserved_quantity) > 0')
                ->limit(8)
                ->get();
        });

        // ╔════════════════════════════════════════════════════╗
        // ║ EXPLORE (Latest Products Grid)                     ║
        // ╚════════════════════════════════════════════════════╝
        $latestProducts = Product::query()
            ->where('status', 'available')
            ->with('mainImage')
            ->orderByDesc('created_at')
            ->limit(24)
            ->get();

        return view('home.index', [
            'featuredProduct'   => $featuredProduct,
            'categories'        => $categories,
            'dealProducts'      => $dealProducts,
            'newProducts'       => $newProducts,
            'bestSellers'       => $bestSellers,
            'trendingProducts'  => $trendingProducts,
            'latestProducts'    => $latestProducts,
        ]);
    }

    /**
     * Clear homepage cache (call after product updates)
     */
    public function clearCache()
    {
        Cache::forget('home_featured_product');
        Cache::forget('home_categories');
        Cache::forget('home_deal_products');
        Cache::forget('home_new_products');
        Cache::forget('home_best_sellers');
        Cache::forget('home_trending');

        return back()->with('success', 'Homepage cache cleared');
    }
}
