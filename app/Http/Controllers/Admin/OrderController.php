<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Notifications\OrderStatusNotification;


class OrderController extends Controller
{
    //

    public function index(Request $request)
    {

         $orders = Order::with('user')
            ->when($request->search, function($query) use($request){

                $query->where('id',$request->search)
                    ->orWhereHas('user', function($q) use($request){

                        $q->where('name','like','%'.$request->search.'%');

                });

            })
            ->when($request->status, function($query) use($request){

            $query->where(
                'order_status',
                $request->status
            );

        })
        ->latest()
        ->paginate(10);


        return view(
            'admin.orders.index',
            compact('orders')
        );
    }
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,confirmed,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->order_status;

        $order->update([
            'order_status' => $request->order_status
        ]);

            $order->user->notify(
                new OrderStatusNotification($order)
            );

        // 🔥 إذا تحول إلى confirmed → نقص من المخزون
        if ($oldStatus != 'confirmed' && $request->order_status == 'confirmed') {

            foreach ($order->items as $item) {

                $product = $item->product;

                // تأكد في مخزون كافي
                if ($product->quantity < $item->quantity) {
                    return back()->with('error', 'Not enough stock for ' . $product->name);
                 }

                // $product->decrement('quantity', $item->quantity);
            }
        }

        // 🔥 إذا تم إلغاء الطلب → رجّع المخزون
        if ($oldStatus != 'cancelled' && $request->order_status == 'cancelled') {

            foreach ($order->items as $item) {

                $item->product->increment('quantity', $item->quantity);
            }
         }

         return back()->with('success', 'Order updated successfully');
    }

public function show(Order $order)
    {

        $order->load([
            'user',
            'items.product'
        ]);


        return view(
            'admin.orders.show',
            compact('order')
        );

    }


}
