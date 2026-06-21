@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto py-10 px-4">


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

        <h1 class="text-4xl font-bold text-gray-800">

            Order #{{ $order->id }}

        </h1>


        <p class="text-gray-500 mt-2">

            Placed at
            {{ $order->created_at->format('d M Y - H:i') }}

        </p>


    </div>



@php

$statusColor = match($order->order_status){

    'pending'
    => 'bg-yellow-100 text-yellow-700',

    'confirmed'
    => 'bg-blue-100 text-blue-700',

    'shipped'
    => 'bg-purple-100 text-purple-700',

    'delivered'
    => 'bg-green-100 text-green-700',

    'cancelled'
    => 'bg-red-100 text-red-700',

    default
    => 'bg-gray-100 text-gray-700'

};

@endphp



<span class="px-5 py-2 rounded-full font-bold {{ $statusColor }}">

    {{ ucfirst($order->order_status) }}

</span>


</div>





<!-- Cards -->


<div class="grid md:grid-cols-3 gap-6">



<!-- Order Info -->

<div class="bg-white rounded-2xl shadow p-6">

<h2 class="text-xl font-bold mb-5">
Order Info
</h2>


<div class="space-y-3 text-gray-600">


<p>
<span class="font-bold">Total:</span>

${{ $order->total_price }}

</p>


<p>

<span class="font-bold">Shipping:</span>

${{ $order->shipping_cost }}

</p>



<p>

<span class="font-bold">
Payment:
</span>

{{ ucfirst($order->payment_method) }}

</p>


<p>

<span class="font-bold">
Payment Status:
</span>

{{ ucfirst($order->payment_status) }}

</p>


<p>

<span class="font-bold">
discount coupon:
</span>

${{ $order->discount ? $order->discount : 0 }}

</p>



</div>


</div>





<!-- Customer -->


<div class="bg-white rounded-2xl shadow p-6">


<h2 class="text-xl font-bold mb-5">
Customer
</h2>


<p class="text-gray-700 font-semibold">

{{ $order->user->name }}

</p>


<p class="text-gray-500">

{{ $order->user->email }}

</p>


</div>





<!-- Summary -->

<div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl p-6">


<h2 class="text-xl font-bold mb-5">

Summary

</h2>


<p>

Total Items

</p>


<h1 class="text-4xl font-bold">

{{ $order->items->count() }}

</h1>


</div>



</div>








<!-- Products -->


<div class="mt-10">


<h2 class="text-2xl font-bold mb-5">

Order Items

</h2>



<div class="bg-white rounded-2xl shadow overflow-hidden">



<table class="w-full">


<thead class="bg-gray-100">


<tr>

<th class="p-4 text-left">
Product
</th>


<th class="p-4">
Price
</th>


<th class="p-4">
Qty
</th>


<th class="p-4">
Total
</th>


</tr>


</thead>



<tbody>



@foreach($order->items as $item)


<tr class="border-t">


<td class="p-4 flex items-center gap-4">


<img

src="{{ asset('storage/'.$item->product->image) }}"

class="w-14 h-14 rounded-xl object-cover">


<span class="font-semibold">

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








<!-- TRACKING TIMELINE -->


<div class="mt-12 bg-white shadow rounded-2xl p-6">


<h2 class="text-2xl font-bold mb-8">

Order Tracking

</h2>




<div class="relative">


<!-- vertical line -->

<div class="absolute left-5 top-0 h-full w-1 bg-gray-200"></div>




@foreach($order->histories as $history)



@php

$dot = match($history->status){

'pending'
=>'bg-yellow-500',

'confirmed'
=>'bg-blue-500',

'shipped'
=>'bg-purple-500',

'delivered'
=>'bg-green-500',

'cancelled'
=>'bg-red-500',

default
=>'bg-gray-500'

};


@endphp





<div class="relative flex gap-6 mb-8">





<!-- circle -->


<div class="z-10 w-10 h-10 rounded-full
{{ $dot }}
text-white flex items-center justify-center shadow">


✓


</div>





<!-- content -->


<div class="flex-1 bg-gray-50 rounded-xl p-4">


<div class="flex justify-between">


<h3 class="font-bold text-lg">


{{ ucfirst($history->status) }}


</h3>



<span class="text-sm text-gray-500">

{{ $history->created_at->format('d M Y H:i') }}

</span>


</div>



<p class="text-gray-600 mt-2">

Order status updated to

<b>
{{ ucfirst($history->status) }}
</b>


</p>



</div>



</div>




@endforeach




</div>



</div>







</div>


@endsection
