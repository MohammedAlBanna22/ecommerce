@extends('layouts.app')


@section('content')

<div class="max-w-7xl mx-auto py-10 px-4">


    <!-- Header -->
    <div class="flex justify-between items-center mb-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                My Orders
            </h1>

            <p class="text-gray-500">
                Manage and track your purchases
            </p>
        </div>


    </div>



    <!-- Orders -->

    <div class="space-y-6">


    @forelse($orders as $order)


        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6">


            <!-- Top -->

            <div class="flex justify-between items-center">


                <div>

                    <h2 class="text-xl font-bold">
                        Order #{{ $order->id }}
                    </h2>


                    <p class="text-gray-500 text-sm">
                        {{ $order->created_at->format('d M Y') }}
                    </p>

                </div>



                <!-- Status -->

                @php

                $statusColor = match($order->order_status){

                    'pending' =>
                    'bg-yellow-100 text-yellow-700',

                    'confirmed' =>
                    'bg-blue-100 text-blue-700',

                    'shipped' =>
                    'bg-purple-100 text-purple-700',

                    'delivered' =>
                    'bg-green-100 text-green-700',

                    'cancelled' =>
                    'bg-red-100 text-red-700',

                    default =>
                    'bg-gray-100 text-gray-700'
                };

                @endphp



                <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusColor }}">

                    {{ ucfirst($order->order_status) }}

                </span>


            </div>




            <!-- Info -->

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6">


                <div class="bg-gray-50 p-4 rounded-xl">

                    <p class="text-gray-500 text-sm">
                        Total
                    </p>

                    <p class="text-xl font-bold text-blue-600">

                        ${{ $order->total_price }}

                    </p>

                </div>



                <div class="bg-gray-50 p-4 rounded-xl">

                    <p class="text-gray-500 text-sm">
                        Payment
                    </p>

                    <p class="font-semibold">

                        {{ ucfirst($order->payment_status) }}

                    </p>

                </div>



                <div class="bg-gray-50 p-4 rounded-xl">

                    <p class="text-gray-500 text-sm">
                        Method
                    </p>

                    <p class="font-semibold">

                        {{ ucfirst($order->payment_method) }}

                    </p>

                </div>


            </div>




            <!-- Actions -->

            <div class="mt-6 flex justify-end">


                <a href="{{ route('orders.show',$order->id) }}"

                class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition">

                    View Order

                </a>


            </div>



        </div>



    @empty


        <div class="bg-white rounded-xl p-10 text-center shadow">

            <h2 class="text-xl font-bold">
                No Orders Yet
            </h2>

            <p class="text-gray-500 mt-2">
                Your orders will appear here.
            </p>

        </div>


    @endforelse


    </div>


</div>


@endsection
