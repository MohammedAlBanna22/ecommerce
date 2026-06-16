<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    //
     public function download(Order $order)
    {


        $order->load([
            'user',
            'items.product'
        ]);



        $pdf = Pdf::loadView(
            'invoices.order',
            compact('order')
        );



        return $pdf->download(
            'invoice-'.$order->id.'.pdf'
        );


    }
}