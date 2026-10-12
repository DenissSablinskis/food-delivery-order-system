<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{

    public function index() {

        $orders = auth()->user()->orders()->with('orderedProducts')->get();

        foreach ($orders as $order) {

            $total = $order->orderedProducts->sum(function ($orderedProduct) {
                return $orderedProduct->product_count * $orderedProduct->unit_price_at_purchase;
            });
            
            $order->total = $total;
        }
        
        return view('pages.orders.index', compact('orders'));  
    }

    public function invoice(Order $order) {

        $order->load('orderedProducts');

        $total = $order->orderedProducts->sum(function ($orderedProduct) {
            return $orderedProduct->product_count *
                $orderedProduct->unit_price_at_purchase;
        });

        // Ģenerē PDF no skata un pasūtījuma datiem
        $pdf = Pdf::loadView('pages.orders.invoice', [
            'order' => $order,
            'total' => $total,
        ]);

        // Atgriež PDF lejupielādei ar nosaukumu "invoice-{order_id}.pdf"
        return $pdf->setPaper('a4')->download("invoice-{$order->id}.pdf");
    }
}
