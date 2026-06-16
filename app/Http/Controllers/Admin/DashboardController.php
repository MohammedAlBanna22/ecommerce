<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
       public function index()
    {

        $totalProducts = Product::count();

        $totalOrders = Order::count();

        $totalCustomers = User::where('role','customer')->count();

        $totalSales = Order::where('order_status','!=','cancelled')
            ->sum('total_price');

        //$lowStock = Product::where('quantity','<=',5)->count();
        $lowStockProducts = Product::where('quantity','<=',5)
        ->orderBy('quantity')
        ->get();

        $topProducts = OrderItem::select(
        'product_id',
        DB::raw('SUM(quantity) as total_sold')
        )
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->with('product')
        ->take(5)
        ->get();

        $ordersStatus = Order::select(
            'order_status',
            DB::raw('count(*) as total')
            )
        ->groupBy('order_status')
        ->get();

        $monthlySales = Order::select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('SUM(total_price) as total')
        )
         ->where('order_status','!=','cancelled')
        ->groupBy('month')
        ->orderBy('month')
         ->get();


        $salesData = [];

        for ($i = 1; $i <= 12; $i++) {

             $month = $monthlySales->firstWhere('month', $i);

            $salesData[] = [
            'month' => date('M', mktime(0,0,0,$i,1)),
            'sales' => $month->total ?? 0
            ];

        }
        $statusData = Order::select(
            'order_status',
            DB::raw('count(*) as total')
            )
        ->groupBy('order_status')
        ->get();


        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalCustomers',
            'totalSales',
            'lowStockProducts',
            'topProducts',
            'ordersStatus',
            'monthlySales',
            'salesData',
            'statusData'

        ));
    }
}