@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto py-12 px-4">


<h1 class="text-4xl font-bold mb-8">
    My Cart 🛒
</h1>


@if($cart && $cart->items->count())


<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">


    <!-- Items -->
    <div class="lg:col-span-2 space-y-5">


    @foreach($cart->items as $item)


    <div class="bg-white rounded-2xl shadow-lg p-5 flex justify-between items-center hover:shadow-xl transition">


        <!-- Click area -->
        <a href="{{ route('checkout.index') }}"
           class="flex items-center gap-5 flex-1">


            <img
            src="{{ asset('storage/'.$item->product->image) }}"
            class="w-24 h-24 rounded-xl object-cover">


            <div>


                <h2 class="text-xl font-bold">
                    {{ $item->product->name }}
                </h2>


                <p class="text-gray-500">
                    Quantity:
                    {{ $item->quantity }}
                </p>


                <p class="text-blue-600 font-bold mt-2">
                    ${{ $item->price }}
                </p>


            </div>


        </a>




        <div class="flex flex-col gap-3">


            <!-- Update -->

            <form action="{{ route('cart.update',$item->id) }}"
                  method="POST">

                @csrf
                @method('PUT')


                <input
                type="number"
                name="quantity"
                value="{{ $item->quantity }}"
                min="1"
                class="w-20 border rounded px-2">


                <button
                class="bg-blue-600 text-white px-3 py-1 rounded">

                    Update

                </button>


            </form>



            <!-- Delete -->

            <form action="{{ route('cart.destroy',$item->id) }}"
                  method="POST">

                @csrf
                @method('DELETE')


                <button
                class="bg-red-500 text-white px-4 py-1 rounded">

                    Remove

                </button>


            </form>


        </div>



    </div>


    @endforeach


    </div>





    <!-- Summary -->

    <div class="bg-white rounded-2xl shadow-lg p-6 h-fit">


        <h2 class="text-2xl font-bold mb-5">
            Cart Summary
        </h2>



        <div class="flex justify-between text-gray-600">

            <span>
                Items
            </span>

            <span>
                {{ $cart->items->count() }}
            </span>

        </div>



        <div class="border-t mt-4 pt-4 flex justify-between text-xl font-bold">


            <span>
                Total
            </span>


            <span class="text-blue-600">

                ${{ $total }}

            </span>


        </div>



        <!-- Checkout -->

        <a href="{{ route('checkout.index') }}"
           class="block text-center mt-6 bg-black text-white py-3 rounded-xl hover:bg-gray-800">

            Go To Checkout

        </a>



    </div>



</div>


@else


<div class="text-center p-10">

    <h2 class="text-2xl">
        Cart is empty 🛒
    </h2>

</div>


@endif


</div>

@endsection