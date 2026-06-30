<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusNotification;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    //

    public function index(Request $request)
    {

            $orders = Order::with('user')
            ->withCount('items')
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
    public function updateStatus(
    Request $request,
    Order $order,
    OrderStatusService $service
    )
    {

        $request->validate([
            'order_status'=>
            'required|in:
            pending,processing,confirmed,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
            'shipping_carrier' => 'nullable|string|max:50',

        ]);



        try {


            $service->update(
                $order,
                $request->order_status,
                 $request->tracking_number,
                $request->shipping_carrier,
            );



            $order->user->notify(
                new OrderStatusNotification($order)
            );



            return back()
            ->with(
                'success',
                'Order updated successfully'
            );



        }catch(\Exception $e){


            return back()
            ->with(
                'error',
                $e->getMessage()
            );

        }

    }

public function show(Order $order)
    {

        $order->load([
            'user',
            'items.product',
            'histories'
        ]);


        return view(
            'admin.orders.show',
            compact('order')
        );

    }

    public function markShipped(Request $request, Order $order, OrderStatusService $service)
{
    $request->validate([
        'tracking_number'  => 'required|string|max:100',
        'shipping_carrier' => 'required|string|max:50',
    ]);

    if ($order->order_status !== 'confirmed') {
        return back()->with('error', 'Order must be confirmed before shipping.');
    }

    try {
        $service->update(
            $order,
            'shipped',
            $request->tracking_number,
            $request->shipping_carrier,
        );

        $order->user->notify(new OrderStatusNotification($order));

        return back()->with('success', 'Order marked as shipped!');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}


}