<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Admin Dashboard</title>

<script src="https://cdn.tailwindcss.com"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>


<body class="bg-gray-100">


<div class="max-w-7xl mx-auto p-6">


<h1 class="text-3xl font-bold mb-8">
Admin Dashboard
</h1>




<!-- Cards -->

<div class="grid md:grid-cols-4 gap-5 mb-8">


<div class="bg-white rounded-xl shadow p-5">

<p class="text-gray-500">
Products
</p>

<h2 class="text-3xl font-bold">
{{ $totalProducts }}
</h2>

</div>



<div class="bg-white rounded-xl shadow p-5">

<p class="text-gray-500">
Orders
</p>

<h2 class="text-3xl font-bold">
{{ $totalOrders }}
</h2>

</div>




<div class="bg-white rounded-xl shadow p-5">

<p class="text-gray-500">
Customers
</p>

<h2 class="text-3xl font-bold">
{{ $totalCustomers }}
</h2>

</div>




<div class="bg-white rounded-xl shadow p-5">

<p class="text-gray-500">
Sales
</p>

<h2 class="text-3xl font-bold text-green-600">

${{ number_format($totalSales,2) }}

</h2>

</div>


</div>





<!-- Charts -->

<div class="grid md:grid-cols-2 gap-6 mb-8">


<div class="bg-white p-5 rounded-xl shadow">


<h2 class="font-bold mb-4">
Monthly Sales
</h2>


<canvas id="salesChart"></canvas>


</div>





<div class="bg-white p-5 rounded-xl shadow">


<h2 class="font-bold mb-4">
Orders Status
</h2>


<canvas id="statusChart"></canvas>


</div>



</div>





<!-- Stock -->

<div class="grid md:grid-cols-2 gap-6">



<!-- Low Stock -->


<div class="bg-white rounded-xl shadow p-5">


<h2 class="font-bold mb-4 text-red-500">
Low Stock Products
</h2>



<table class="w-full">


<thead>

<tr class="border-b">

<th class="text-left">
Product
</th>


<th>
Available
</th>


<th>
Reserved
</th>


</tr>

</thead>



<tbody>


@forelse($lowStockProducts as $product)


<tr class="border-b">


<td>

{{ $product->name }}

</td>


<td>

{{ $product->quantity }}

</td>


<td class="text-orange-500">

{{ $product->reserved_quantity }}

</td>



</tr>



@empty

<tr>

<td colspan="3" class="text-center">

No low stock

</td>


</tr>


@endforelse



</tbody>


</table>



</div>





<!-- Reserved -->

<div class="bg-white rounded-xl shadow p-5">


<h2 class="font-bold mb-4">
Reserved Stock
</h2>



<table class="w-full">


<thead>

<tr class="border-b">


<th>
Product
</th>


<th>
Reserved
</th>


</tr>


</thead>



<tbody>


@foreach($reservedProducts as $product)


<tr class="border-b">


<td>

{{ $product->name }}

</td>


<td class="text-blue-600">

{{ $product->reserved_quantity }}

</td>


</tr>



@endforeach



</tbody>



</table>


</div>




</div>







<!-- Top Products -->


<div class="bg-white rounded-xl shadow p-5 mt-6">


<h2 class="font-bold mb-5">
Top Selling Products
</h2>


<table class="w-full">


<thead>

<tr class="border-b">


<th>
Product
</th>


<th>
Sold
</th>


</tr>

</thead>



<tbody>



@foreach($topProducts as $item)


<tr class="border-b">


<td>

{{ $item->product->name ?? '-' }}

</td>


<td>

{{ $item->total_sold }}

</td>



</tr>



@endforeach



</tbody>


</table>



</div>





</div>





<script>


// Sales Chart


new Chart(
document.getElementById('salesChart'),
{

type:'line',

data:{


labels:@json(
collect($salesData)
->pluck('month')
),


datasets:[{

label:'Sales',


data:@json(
collect($salesData)
->pluck('sales')
),


borderWidth:2


}]


},


options:{

responsive:true

}


});







// Status Chart


new Chart(
document.getElementById('statusChart'),
{

type:'doughnut',


data:{


labels:@json(
$ordersStatus->pluck('order_status')
),



datasets:[{


data:@json(
$ordersStatus->pluck('total')
),


borderWidth:1


}]


}

});



</script>



</body>

</html>
