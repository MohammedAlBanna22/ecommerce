@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto py-12 px-4">


    <!-- Header -->
    <div class="mb-8">

        <h1 class="text-4xl font-bold text-gray-800">
            Checkout
        </h1>

        <p class="text-gray-500 mt-2">
            Review your order before confirming
        </p>

    </div>
@if($errors->any())

<div class="bg-red-100 border border-red-400 text-red-700 px-5 py-4 rounded-xl mb-6">


    <h3 class="font-bold mb-2">
        Please fix these errors:
    </h3>


    <ul class="list-disc ml-5">

        @foreach($errors->all() as $error)

            <li>
                {{ $error }}
            </li>

        @endforeach

    </ul>


</div>

@endif


@if(!$cart || $cart->items->count() == 0)


    <div class="bg-white shadow rounded-2xl p-10 text-center">

        <h2 class="text-2xl font-bold text-gray-700">
            Your cart is empty 🛒
        </h2>

        <p class="text-gray-500 mt-2">
            Add some products before checkout.
        </p>

    </div>



@else


<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">



    <!-- Products -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">


        <h2 class="text-2xl font-bold mb-6">
            Order Items
        </h2>



        <div class="space-y-5">


        @foreach($cart->items as $item)


            <div class="flex items-center justify-between border-b pb-5">


                <div class="flex items-center gap-4">


                    @if($item->product->image)

                    <img
                    src="{{ asset('storage/'.$item->product->image) }}"
                    class="w-20 h-20 rounded-xl object-cover">

                    @endif



                    <div>


                        <h3 class="font-bold text-lg">

                            {{ $item->product->name }}

                        </h3>



                        <p class="text-gray-500">

                            Quantity:
                            {{ $item->quantity }}

                        </p>


                        <p class="text-blue-600 font-semibold">

                            ${{ $item->price }}

                        </p>


                    </div>


                </div>



                <div class="font-bold text-lg">

                    ${{ $item->price * $item->quantity }}

                </div>



            </div>



        @endforeach


        </div>


    </div>





    <!-- Summary -->
    <div class="bg-white rounded-2xl shadow p-6 h-fit">


        <h2 class="text-2xl font-bold mb-6">
            Order Summary
        </h2>



        @php

        $total = $cart->items->sum(
            fn($item)
            => $item->price * $item->quantity
        );

        @endphp



        <div class="flex justify-between mb-4 text-gray-600">

            <span>
                Items
            </span>

            <span>
                {{ $cart->items->count() }}
            </span>

        </div>




        <div class="flex justify-between text-xl font-bold border-t pt-4">


            <span>
                Total
            </span>


            <span class="text-blue-600">

                ${{ $total }}

            </span>


        </div>



        <form method="POST"
              action="{{ route('checkout.store') }}"
              class="mt-8">

            @csrf
            <label for="coupon_code" class="block mb-2 font-semibold text-gray-700">
                Coupon Code
            </label>
            <input type="text" name="coupon_code" placeholder="Coupon" class="border border-gray-300 rounded-xl py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button
            class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition font-bold shadow-lg">


                Confirm Order


            </button>


        </form>



    </div>



</div>


@endif


</div>

@endsection
