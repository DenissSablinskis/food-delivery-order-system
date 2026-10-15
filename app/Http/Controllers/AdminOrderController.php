<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Requests\UpdateOrderStatusRequest;

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

    public function updateStatus(Order $order, UpdateOrderStatusRequest $request) {

        $data = $request->validated();

        $order->update([
            'status' => $data['status'],
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Pasūtījuma statuss veiksmīgi atjaunināts!');
    }
}
