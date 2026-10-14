<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index() {
        $orders = Order::with('orderedProducts.product')->get();
        foreach ($orders as $order) {
            $total = 0;
            foreach ($order->orderedProducts as $orderedProduct) {
                $total += $orderedProduct->product_count * $orderedProduct->unit_price_at_purchase;
            }
            $order->total = $total;
        }
        return view('pages.admin.orders.index', compact('orders'));
    }
}
