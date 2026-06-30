<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderStatusService
{

    public function update(Order $order, string $status, ?string $trackingNumber = null, ?string $carrier = null)
    {

        return DB::transaction(function () use ($order, $status, $trackingNumber, $carrier) {


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

            $updateData = ['order_status' => $status];

            // لما يصير shipped، نسجل وقت الشحن
            if ($status === 'shipped') {
                    $updateData['shipped_at'] = now();
                    $updateData['tracking_number']   = $trackingNumber;
                    $updateData['shipping_carrier']  = $carrier;

                    // tracking_number يجي من الـ request — راح نمررها للـ service
                    // if (!empty($trackingNumber)) {
                    //     $updateData['tracking_number'] = $trackingNumber;
                    //     $updateData['shipping_carrier'] = $carrier;
                    // }
                }

                // لما يصير delivered
                if ($status === 'delivered') {
                    $updateData['delivered_at'] = now();
                }

                $order->update($updateData);

            // $order->update([
            //     'order_status'=>$status
            // ]);



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