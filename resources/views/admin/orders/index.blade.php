@extends('layouts.app')


@section('content')


<div class="max-w-7xl mx-auto py-12 px-4">



    <!-- Header -->
    <div class="flex justify-between items-center mb-8">


        <div>

            <h1 class="text-4xl font-bold text-gray-800">
                Orders Management
            </h1>

            <p class="text-gray-500 mt-2">
                Manage all customer orders
            </p>

        </div>



        <div class="bg-blue-100 text-blue-700 px-5 py-3 rounded-xl font-bold">

            Total Orders:
            {{ $orders->count() }}

        </div>


    </div>


<form class="flex gap-3 mb-5">


<input
name="search"
placeholder="Search order or customer"
class="border p-2 rounded">


<select name="status"
class="border p-2 rounded">


<option value="">
All Status
</option>


<option value="pending">
Pending
</option>


<option value="confirmed">
Confirmed
</option>


<option value="shipped">
Shipped
</option>


<option value="delivered">
Delivered
</option>


<option value="cancelled">
Cancelled
</option>


</select>


<button class="bg-blue-600 text-white px-4 rounded">
Search
</button>


</form>



    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">



    @foreach($orders as $order)



        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6">



            <!-- Top -->
            <div class="flex justify-between items-start mb-5">


                <div>

                    <h2 class="text-xl font-bold">
                        Order #{{ $order->id }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        {{ $order->created_at->format('d M Y') }}
                    </p>

                </div>



                @php

                $color = match($order->order_status){

                    'pending' =>
                    'bg-yellow-100 text-yellow-700',

                    'confirmed' =>
                    'bg-blue-100 text-blue-700',

                    'shipped' =>
                    'bg-purple-100 text-purple-700',

                    'delivered' =>
                    'bg-green-100 text-green-700',

                    default =>
                    'bg-gray-100 text-gray-700'
                };

                @endphp



                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $color }}">

                    {{ ucfirst($order->order_status) }}

                </span>


            </div>





            <!-- Customer -->
            <div class="space-y-3 text-gray-600">


                <p>👤 <span class="font-semibold">{{ $order->user->name }}</span></p>

                <p>📧 {{ $order->user->email }}</p>

                <p>💰 <span class="font-bold text-blue-600">${{ $order->total_price }}</span></p>

                <p>📦 Items: {{ $order->items->count() }}</p>


            </div>





            <!-- Update Status Form (NEW 🔥) -->
            <form method="POST"
                  action="{{ route('admin.orders.updateStatus', $order->id) }}"
                  class="mt-5 flex flex-col gap-3">


                @csrf
                @method('PUT')


                <select name="order_status"
                        class="border border-gray-300 rounded-xl px-3 py-2 text-sm
                               focus:ring-2 focus:ring-blue-500">


                    <option value="pending"
                    {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>


                    <option value="confirmed"
                    {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>
                        Confirmed
                    </option>


                    <option value="shipped"
                    {{ $order->order_status == 'shipped' ? 'selected' : '' }}>
                        Shipped
                    </option>


                    <option value="delivered"
                    {{ $order->order_status == 'delivered' ? 'selected' : '' }}>
                        Delivered
                    </option>


                    <option value="cancelled"
                    {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>


                </select>



                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-xl shadow-md hover:bg-blue-700 transition font-semibold">

                    Update Status

                </button>


            </form>



            <!-- View Button -->
            <a href="{{ route('admin.orders.show',$order->id) }}"
               class="block mt-3 text-center bg-gray-800 text-white py-2 rounded-xl hover:bg-gray-700">

                View Order

            </a>



        </div>



    @endforeach



    </div>



</div>


@endsection
