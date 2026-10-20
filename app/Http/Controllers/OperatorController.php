<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Validation\Rule;

class OperatorController extends Controller
{
    public function index() {

        $orders = auth()->user()->operatorOrders;
        $couriers = User::where('role', 'courier')->get();

        return view('pages.operator.orders.index', compact('orders', 'couriers'));
    }

    public function selectCourier(Order $order, Request $request) {

        if ($order->operator_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'delivery_person_id' => ['required', Rule::exists('users', 'id')->where('role', 'courier')]  
        ]);

        $order->update($data);

        return redirect()->route('operator.orders.index')->with('success', 'Pasūtījumam tika piešķirts kurjers!');
        
    }

    public function startProcessing(Order $order) {

        if ($order->operator_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'new') {
            abort(422, 'Šo pasūtījumu nevar paņemt apstrādē!');
        }

        $order->update([
            'status' => 'in_process'
        ]);

        return redirect()->route('operator.orders.index')->with('success', 'Pasūtījums ir paņemts apstrādē!');
        
    }

}
