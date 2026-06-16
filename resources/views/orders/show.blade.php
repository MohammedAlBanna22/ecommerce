@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto py-10 px-4">

<a href="{{ route('orders.index') }}"
   class="inline-flex items-center gap-2 mb-6
          bg-gray-800 text-white
          px-5 py-2 rounded-xl
          hover:bg-gray-700 transition">

    ← Back to Orders

</a>
    <!-- Header -->
    <div class="flex justify-between items-start mb-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Order #{{ $order->id }}
            </h1>

            <p class="text-gray-500 mt-1">
                Placed at {{ $order->created_at->format('d M Y - H:i') }}
            </p>
        </div>


        <!-- Status -->
        @php
        $statusColor = match($order->order_status){

            'pending' => 'bg-yellow-100 text-yellow-700',
            'confirmed' => 'bg-blue-100 text-blue-700',
            'shipped' => 'bg-purple-100 text-purple-700',
            'delivered' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700'
        };
        @endphp

        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusColor }}">
            {{ ucfirst($order->order_status) }}
        </span>

    </div>



    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


        <!-- Order Info -->
        <div class="bg-white shadow rounded-2xl p-5">

            <h2 class="text-lg font-bold mb-4">Order Info</h2>

            <p class="text-gray-600">
                <span class="font-semibold">Total:</span>
                ${{ $order->total_price }}
            </p>

            <p class="text-gray-600 mt-2">
                <span class="font-semibold">Shipping:</span>
                ${{ $order->shipping_cost }}
            </p>

            <p class="text-gray-600 mt-2">
                <span class="font-semibold">Payment Method:</span>
                {{ ucfirst($order->payment_method) }}
            </p>

            <p class="text-gray-600 mt-2">
                <span class="font-semibold">Payment Status:</span>
                {{ ucfirst($order->payment_status) }}
            </p>

        </div>



        <!-- Shipping / User -->
        <div class="bg-white shadow rounded-2xl p-5">

            <h2 class="text-lg font-bold mb-4">Customer</h2>

            <p class="text-gray-600">
                {{ $order->user->name }}
            </p>

            <p class="text-gray-500 text-sm">
                {{ $order->user->email }}
            </p>

        </div>



        <!-- Summary -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl p-5">

            <h2 class="text-lg font-bold mb-4">Summary</h2>

            <p class="text-sm">Total Items</p>
            <p class="text-2xl font-bold">
                {{ $order->items->count() }}
            </p>

        </div>

    </div>



    <!-- Items -->
    <div class="mt-10">

        <h2 class="text-2xl font-bold mb-5">
            Order Items
        </h2>


        <div class="bg-white shadow rounded-2xl overflow-hidden">


            <table class="w-full text-left">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4">Product</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Qty</th>
                        <th class="p-4">Total</th>
                    </tr>
                </thead>


                <tbody>

                @foreach($order->items as $item)

                    <tr class="border-t">

                        <td class="p-4 flex items-center gap-3">

                            <img src="{{ asset('storage/'.$item->product->image) }}"
                                 class="w-12 h-12 rounded object-cover">

                            <span>
                                {{ $item->product->name }}
                            </span>

                        </td>

                        <td class="p-4">
                            ${{ $item->price }}
                        </td>

                        <td class="p-4">
                            {{ $item->quantity }}
                        </td>

                        <td class="p-4 font-bold text-blue-600">
                            ${{ $item->price * $item->quantity }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
