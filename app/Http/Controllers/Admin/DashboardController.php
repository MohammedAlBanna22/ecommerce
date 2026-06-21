<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{

    public function index()
    {


        /*
        |--------------------------------------------------------------------------
        | Cards
        |--------------------------------------------------------------------------
        */


        $totalProducts = Product::count();


        $totalOrders = Order::count();



        $totalCustomers = User::where(
            'role',
            'customer'
        )->count();



        // المبيعات الحقيقية فقط
        $totalSales = Order::where(
            'order_status',
            'delivered'
        )
        ->sum('total_price');




        /*
        |--------------------------------------------------------------------------
        | Stock
        |--------------------------------------------------------------------------
        */


        $lowStockProducts = Product::whereColumn(
            'quantity',
            '<=',
            'reserved_quantity'
        )
        ->orWhere('quantity','<=',5)
        ->orderBy('quantity')
        ->get();




        /*
        |--------------------------------------------------------------------------
        | Reserved Stock
        |--------------------------------------------------------------------------
        */


        $reservedProducts = Product::where(
            'reserved_quantity',
            '>',
            0
        )
        ->orderByDesc(
            'reserved_quantity'
        )
        ->take(5)
        ->get();




        /*
        |--------------------------------------------------------------------------
        | Top Selling
        |--------------------------------------------------------------------------
        */


        $topProducts = OrderItem::select(
            'product_id',
            DB::raw(
                'SUM(quantity) as total_sold'
            )
        )
        ->whereHas(
            'order',
            function($q){

                $q->where(
                    'order_status',
                    'delivered'
                );

            }
        )
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->with('product')
        ->take(5)
        ->get();





        /*
        |--------------------------------------------------------------------------
        | Orders Status Chart
        |--------------------------------------------------------------------------
        */


        $ordersStatus = Order::select(
            'order_status',
            DB::raw(
                'count(*) as total'
            )
        )
        ->groupBy('order_status')
        ->get();






        /*
        |--------------------------------------------------------------------------
        | Monthly Sales
        |--------------------------------------------------------------------------
        */


        $monthlySales = Order::select(
            DB::raw(
                'MONTH(created_at) as month'
            ),
            DB::raw(
                'SUM(total_price) as total'
            )
        )
        ->where(
            'order_status',
            'delivered'
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();





        $salesData = [];


        for($i=1;$i<=12;$i++)
        {


            $month =
            $monthlySales
            ->firstWhere(
                'month',
                $i
            );


            $salesData[]=[

                'month'=>date(
                    'M',
                    mktime(
                        0,
                        0,
                        0,
                        $i,
                        1
                    )
                ),

                'sales'=>$month->total ?? 0

            ];

        }




        return view(
            'admin.dashboard',
            compact(
                'totalProducts',
                'totalOrders',
                'totalCustomers',
                'totalSales',
                'lowStockProducts',
                'reservedProducts',
                'topProducts',
                'ordersStatus',
                'salesData'
            )
        );

    }

}
