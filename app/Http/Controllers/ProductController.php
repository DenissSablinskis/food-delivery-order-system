<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderedProduct;

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();
        return view('pages.products.index', compact('products'));
    }
    
    public function cart(){
        return view('pages.cart.index');
    }

    public function checkout(){
        return view('pages.checkout.index');
    }

    public function createOrder(Request $request){
        $user = auth()->user();

        $user->update([
            'address' => $request->address, // Aizpilda lietotāja adresi, ja tā nav norādīta
        ]);

        $order = Order::create([
        'user_id' => auth()->id(),
        'delivery_address' => $request->address,
        'status' => 'New',
        ]);

        foreach ($request->cart as $item) {
            $product = Product::findOrFail($item['id']); // Pārbauda, vai produkts pastāv

            OrderedProduct::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_count' => $item['quantity'],
                'unit_price_at_purchase' => $product->unit_price,
            ]);
        }

        return response()->json($order);
    }
}
