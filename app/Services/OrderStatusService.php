<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderStatusService
{

    public function update(Order $order, string $status)
    {

        return DB::transaction(function () use ($order,$status) {


            $oldStatus = $order->order_status;


            if($oldStatus == 'cancelled')
            {
                throw new \Exception(
                    'Cancelled order cannot be updated'
                );
            }



            /*
            |--------------------------------------------------------------------------
            | CONFIRM ORDER
            |--------------------------------------------------------------------------
            */

            if(
                $oldStatus != 'confirmed'
                &&
                $status == 'confirmed'
            ){

                $this->confirmStock($order);

            }



            /*
            |--------------------------------------------------------------------------
            | CANCEL ORDER
            |--------------------------------------------------------------------------
            */

            if(
                $oldStatus != 'cancelled'
                &&
                $status == 'cancelled'
            ){

                $this->restoreStock($order);

            }



            $order->update([
                'order_status'=>$status
            ]);



            $order->histories()->create([
                'status'=>$status
            ]);



            return $order;


        });

    }



    private function confirmStock(Order $order)
    {

        foreach($order->items as $item)
        {

            $product = Product::lockForUpdate()
                ->find($item->product_id);



            if(!$product->canSell($item->quantity))
            {
                throw new \Exception(
                    "Not enough stock {$product->name}"
                );
            }



            // reserved -> sold
            $product->confirmSale(
                $item->quantity
            );


        }

    }





    private function restoreStock(Order $order)
    {


        foreach($order->items as $item)
        {

            $product = Product::lockForUpdate()
                ->find($item->product_id);



            $product->increment(
                'quantity',
                $item->quantity
            );


        }


    }

}
