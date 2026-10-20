<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class CourierController extends Controller
{
    public function index() {
        $orders = auth()->user()->deliveryOrders;
        return view('pages.courier.orders.index', compact('orders'));
    }

    public function startDelivery(Order $order) {

        if ($order->delivery_person_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'in_process') {
            abort(422, 'Šo pasūtījumu nevar paņemt piegadē!');
        }

        $order->update([
            'status' => 'delivering'
        ]);

        return redirect()->route('courier.orders.index')->with('success', 'Pasūtījuma statuss veiksmīgi atjaunināts!');

    }

    public function complete(Order $order) {

        if ($order->delivery_person_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'delivering') {
            abort(422, 'Šo pasūtījumu nevar atzimēt kā piegādāto!');
        }

        $order->update([
            'status' => 'delivered'
        ]);

        return redirect()->route('courier.orders.index')->with('success', 'Pasūtījuma statuss veiksmīgi atjaunināts!');

    }
}
