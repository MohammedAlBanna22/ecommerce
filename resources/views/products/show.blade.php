<!DOCTYPE html>
<html>

<head>
    <title>Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-gray-100">


<div class="max-w-6xl mx-auto mt-10 bg-white rounded-2xl shadow-lg overflow-hidden">


    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">



        {{-- ===================== --}}
        {{-- IMAGE GALLERY --}}
        {{-- ===================== --}}

        <div class="p-6 space-y-4">


            {{-- MAIN IMAGE --}}

            <div class="bg-gray-100 rounded-2xl h-96 overflow-hidden border">


                @if($product->mainImage)


                    <img id="mainImage"
                    src="{{  $product->image_url }}"
                    class="w-full h-full object-cover transition duration-300">


                @elseif($product->media->first())


                    <img id="mainImage"
                    src="{{ asset('storage/'.$product->media->first()->path) }}"
                    class="w-full h-full object-cover">


                @else


                    <div class="h-full flex items-center justify-center text-gray-500">
                        No Image
                    </div>


                @endif


            </div>



            {{-- COUNT --}}

            <p class="text-sm text-gray-400">
                {{ $product->media->count() }} Images
            </p>



            {{-- THUMBNAILS --}}

            <div class="grid grid-cols-5 gap-3">


                @foreach($product->media as $image)


                <div class="relative">


                    <img

                    src="{{ asset('storage/'.$image->path) }}"

                    onclick="changeImage(this)"

                    class="
                    w-full
                    h-20
                    object-cover
                    rounded-lg
                    cursor-pointer
                    border-2
                    transition

                    {{
                    $image->is_primary
                    ? 'border-blue-600'
                    : 'border-transparent'
                    }}

                    hover:border-blue-500
                    ">


                    @if($image->is_primary)

                    <span
                    class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-2 rounded">

                    MAIN

                    </span>

                    @endif



                </div>


                @endforeach


            </div>



        </div>





        {{-- ===================== --}}
        {{-- PRODUCT DETAILS --}}
        {{-- ===================== --}}


        <div class="p-8 space-y-5">


            {{-- NAME --}}

            <h1 class="text-4xl font-bold text-gray-800">

                {{ $product->name }}

            </h1>




            {{-- CATEGORY --}}


            <p class="text-gray-500">

                Category:

                <span class="font-semibold text-gray-700">

                    {{ $product->category->name ?? 'No Category' }}

                </span>

            </p>





            {{-- PRICE --}}


            <div class="flex items-center gap-4">


                <span class="text-3xl font-bold text-blue-600">


                    ${{ number_format($product->price,2) }}


                </span>



                @if($product->discount_price)


                <span class="text-lg line-through text-gray-400">


                    ${{ number_format($product->discount_price,2) }}


                </span>


                @endif



            </div>







            {{-- DESCRIPTION --}}


            <p class="text-gray-600 leading-relaxed">


                {{ $product->description ?? 'No description available.' }}


            </p>







            {{-- INVENTORY --}}


            <div class="bg-gray-50 rounded-xl p-4 space-y-2">


                <p>

                Total Stock:

                <span class="font-bold">

                {{ $product->quantity }}

                </span>

                </p>




                <p>

                Reserved:

                <span class="font-bold text-orange-500">

                {{ $product->reserved_quantity }}

                </span>


                </p>





                <p>

                Available:


                <span class="font-bold text-green-600">


                {{
                $product->quantity -
                $product->reserved_quantity
                }}


                </span>


                </p>



            </div>







            {{-- STATUS --}}


            <div>


                <span

                class="px-4 py-1 rounded-full text-sm font-medium


                {{

                $product->status == 'available'

                ? 'bg-green-100 text-green-600'

                : 'bg-red-100 text-red-600'

                }}

                ">


                {{ $product->status }}


                </span>


            </div>









            {{-- ACTIONS --}}


            <div class="flex gap-3 pt-6">



                <a

                href="{{ route('products.edit',$product->id) }}"

                class="
                flex-1
                text-center
                bg-yellow-500
                text-white
                py-3
                rounded-xl
                hover:bg-yellow-600">


                Edit Product


                </a>







                <form

                method="POST"

                action="{{ route('products.destroy',$product->id) }}"

                class="flex-1">


                    @csrf
                    @method('DELETE')



                    <button

                    onclick="return confirm('Delete this product?')"

                    class="
                    w-full
                    bg-red-500
                    text-white
                    py-3
                    rounded-xl
                    hover:bg-red-600">


                    Delete


                    </button>


                </form>



            </div>







            <a

            href="{{ route('products.index') }}"

            class="block text-center text-gray-500 hover:text-black">


            ← Back to Products


            </a>





        </div>




    </div>



</div>





<script>


function changeImage(el)
{

    document.getElementById('mainImage').src = el.src;

}


</script>




</body>

</html>
