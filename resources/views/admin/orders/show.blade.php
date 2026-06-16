@extends('layouts.app')


@section('content')


<div class="min-h-screen bg-gray-100 py-12 px-4">


<div class="max-w-7xl mx-auto">



    <!-- Header -->

    <div class="flex justify-between items-center mb-8">


        <div>

            <h1 class="text-4xl font-bold text-gray-800">

                Order #{{ $order->id }}

            </h1>


            <p class="text-gray-500 mt-2">

                Created {{ $order->created_at->format('d M Y H:i') }}

            </p>


        </div>



        @php

        $statusColor = match($order->order_status){

            'pending'=>'bg-yellow-100 text-yellow-700',

            'confirmed'=>'bg-blue-100 text-blue-700',

            'shipped'=>'bg-purple-100 text-purple-700',

            'delivered'=>'bg-green-100 text-green-700',

            'cancelled'=>'bg-red-100 text-red-700',

            default=>'bg-gray-100 text-gray-700'

        };

        @endphp


        <span class="px-5 py-2 rounded-full font-bold {{ $statusColor }}">

            {{ ucfirst($order->order_status) }}

        </span>


    </div>






<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">





<!-- Customer -->

<div class="bg-white rounded-2xl shadow p-6">


<h2 class="text-xl font-bold mb-5">
Customer
</h2>



<div class="space-y-3 text-gray-600">


<p>
👤
<span class="font-semibold">

{{ $order->user->name }}

</span>
</p>


<p>
📧
{{ $order->user->email }}
</p>


</div>


</div>






<!-- Update Status -->


<div class="bg-white rounded-2xl shadow p-6">


<h2 class="text-xl font-bold mb-5">

Update Status

</h2>



{{--  <form method="POST"

action="{{ route('orders.update',$order->id) }}"

class="space-y-4">


@csrf
@method('PUT')



<select name="order_status"

class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">


<option value="pending"
{{ $order->order_status=='pending'?'selected':'' }}>

Pending

</option>



<option value="confirmed"
{{ $order->order_status=='confirmed'?'selected':'' }}>

Confirmed

</option>



<option value="shipped"
{{ $order->order_status=='shipped'?'selected':'' }}>

Shipped

</option>



<option value="delivered"
{{ $order->order_status=='delivered'?'selected':'' }}>

Delivered

</option>



<option value="cancelled"
{{ $order->order_status=='cancelled'?'selected':'' }}>

Cancelled

</option>


</select>





<button

class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition">

Update Status

</button>



</form>  --}}


</div>






<!-- Summary -->


<div class="bg-gradient-to-r from-blue-600 to-purple-600 text-black rounded-2xl shadow p-6">


<h2 class="text-xl font-bold mb-5">

Summary

</h2>



<p class="opacity-80">

Total

</p>


<p class="text-4xl font-bold">

${{ $order->total_price }}

</p>



<p class="mt-5">

Items:

<span class="font-bold">

{{ $order->items->count() }}

</span>

</p>



</div>



</div>







<!-- Products -->


<div class="mt-10 bg-white rounded-2xl shadow overflow-hidden">


<div class="p-6">

<h2 class="text-2xl font-bold">

Order Items

</h2>

</div>




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
<th>
    Action
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




<td class="p-4 text-center">

${{ $item->price }}

</td>




<td class="p-4 text-center">

{{ $item->quantity }}

</td>




<td class="p-4 text-center font-bold text-blue-600">

${{ $item->total }}

</td>

<td class="p-4 text-center">    <a
href="{{ route('orders.invoice',$order->id) }}"
class="bg-blue-600 text-black px-4 py-2 rounded">

Download Invoice

</a>

</tr>


@endforeach


</tbody>


</table>



</div>





</div>

</div>


@endsection
