<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderedProduct;
use App\Http\Requests\UpdateOrderStatusRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateOrderAddressRequest;

class AdminOrderController extends Controller
{
    public function index() {
        $orders = Order::with('orderedProducts.product')->get();
        $products = Product::all();
        foreach ($orders as $order) {
            $total = 0;
            foreach ($order->orderedProducts as $orderedProduct) {
                $total += $orderedProduct->product_count * $orderedProduct->unit_price_at_purchase;
            }
            $order->total = $total;
        }
        return view('pages.admin.orders.index', compact(['orders', 'products']));
    }

    public function updateStatus(Order $order, UpdateOrderStatusRequest $request) {

        try {

            /* DB::transaction() nodrošina operāciju atomaritāti: ja kāda operācija neizdodas, 
            visas transakcijas laikā veiktās datubāzes izmaiņas tiek atceltas. */

            return DB::transaction(function () use ($order, $request) {

                $order = Order::lockForUpdate()->findOrFail($order->id); // findOrFail tiek izmantots, jo pasūtījums tiek meklēts pēc primārās atslēgas (id).

                // Ja pasūtījums jau ir piegādāts, tā statusu vairs nevar mainīt.
                
                if ($order->status === 'delivered') {
                    throw new \RuntimeException('Šī pasūtījuma statusu vairs nevar mainīt!');
                }

                $orderedProducts = $order->orderedProducts()->lockForUpdate()->get(); // get() izpilda SQL vaicājumu un atgriež ar konkrēto pasūtījumu saistīto OrderedProduct modeļu kolekciju.

                foreach ($orderedProducts as $orderedProduct) {
                    $product = $orderedProduct->product()->lockForUpdate()->firstOrFail(); // firstOrFail() atgriež vienu Product modeļa eksemplāru, kas saistīts ar konkrēto pasūtījuma pozīciju, vai izmet izņēmumu, ja ieraksts nav atrasts.
                    $orderedProduct->setRelation('product', $product);
                }

                $data = $request->validated();

                // Ja pasūtījums jau ir atcelts, to nevar atcelt atkārtoti.

                if ($order->status === 'cancelled' && $data['status'] === 'cancelled') {
                    throw new \RuntimeException('Šis pasūtījums jau ir atcelts!');   
                }

                $message = 'Pasūtījuma statuss veiksmīgi atjaunināts!';

                // Ja administrators atceļ pasūtījumu, produkti tiek atgriezti noliktavā.

                if ($data['status'] === 'cancelled') {

                    foreach($orderedProducts as $orderedProduct) {
                        $orderedProduct->product->increment('stock_quantity', $orderedProduct->product_count);
                    }

                    $message = 'Pasūtījums tika atcelts un pasūtītas preces atgrieztas noliktavā!';
                }

                /* Ja pasūtījums tiek atjaunots, vispirms pārbauda, vai noliktavā ir pietiekami daudz produktu.
                Ja produktu pietiek, to daudzums tiek atskaitīts no noliktavas. */

                if($order->status === 'cancelled' && $data['status'] !== 'cancelled') {

                    foreach($orderedProducts as $orderedProduct) {

                        if($orderedProduct->product_count > $orderedProduct->product->stock_quantity) {
                            throw new \RuntimeException('Noliktavā nav pietiekošs produkta ' . $orderedProduct->product->name . ' skaits!');
                        } 
                    }

                    foreach($orderedProducts as $orderedProduct) {

                        $orderedProduct->product->decrement('stock_quantity', $orderedProduct->product_count);

                    }

                }

                $order->update(['status' => $data['status'],]);

                return redirect()->route('admin.orders.index')->with('success', $message);
            });

        } catch (\RuntimeException $e) {

            return redirect()->route('admin.orders.index')->with('error', $e->getMessage());
        }
    }

    public function updateAddress(Order $order, UpdateOrderAddressRequest $request) {

        $data = $request->validated();

        $order->update(['delivery_address' => $data['delivery_address']]);

        return redirect()->route('admin.orders.index')->with('success', 'Adrese veiksmīgi nomainīta!');

    }

    public function increaseProductQuantity(Order $order, OrderedProduct $orderedProduct) {
       
        /*
        * Tiek pārbaudīts, vai objekts $orderedProduct,
        * kura daudzums tiek mainīts, pieder pasūtījumam $order.
        *
        * Metode where() meklē pasūtījuma $order saistītajās
        * orderedProducts pozīcijās ierakstu, kura id sakrīt
        * ar objekta $orderedProduct id.
        *
        * Ja ieraksts eksistē, metode exists() atgriež true,
        * pretējā gadījumā — false.
        */

        if (!$order->orderedProducts()->where('id', $orderedProduct->id)->exists()) {
            abort(404);
        } 

        try {

            DB::transaction(function () use ($order, $orderedProduct) {

                // Pasūtījuma, pasūtījuma pozīcijas un produkta ierakstu bloķēšana uz transakcijas izpildes laiku,
                // lai novērstu konfliktus starp paralēli izpildītiem vaicājumiem.

                $order = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();
                $orderedProduct = OrderedProduct::where('id', $orderedProduct->id)->where('order_id', $order->id)->lockForUpdate()->firstOrFail();
                $product = Product::where('id', $orderedProduct->product->id)->lockForUpdate()->firstOrFail();

                if ($order->status === 'delivered' || $order->status === 'cancelled') {
                    throw new \RuntimeException('Šis pasūtījums ir piegādāts vai atcelts! Mainīt preču daudzumu nav iespējams!');
                }
              
                if ($product->stock_quantity > 0) {
                    $product->decrement('stock_quantity', 1);
                    $orderedProduct->increment('product_count', 1);
                }  else {
                    throw new \RuntimeException('Šī produkta daudzums noliktavā nav pietiekams!');
                }

            });

        } catch (\RuntimeException $e) {

            return redirect()->route('admin.orders.index')->with('error', $e->getMessage());

        } 

        return redirect()->route('admin.orders.index')->with('success', 'Produkta daudzums veiksmīgi nomainīts!');
    }

    public function decreaseProductQuantity(Order $order, OrderedProduct $orderedProduct) {

        if (!$order->orderedProducts()->where('id', $orderedProduct->id)->exists()) {
            abort(404);
        } 

        try {

            DB::transaction(function () use ($order, $orderedProduct) {

                $order = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();
                $orderedProduct = OrderedProduct::where('id', $orderedProduct->id)->where('order_id', $order->id)->lockForUpdate()->firstOrFail();
                $product = Product::where('id', $orderedProduct->product->id)->lockForUpdate()->firstOrFail();

                if ($order->status === 'delivered' || $order->status === 'cancelled') {
                    throw new \RuntimeException('Šis pasūtījums ir piegādāts vai atcelts! Mainīt preču daudzumu nav iespējams!');
                }
              
                if ($orderedProduct->product_count > 1) {
                    $product->increment('stock_quantity', 1);
                    $orderedProduct->decrement('product_count', 1);
                }  else {
                    throw new \RuntimeException('Produkta daudzums pasūtījumā nevar būt mazāks par 1!');
                }

            });

        } catch (\RuntimeException $e) {

            return redirect()->route('admin.orders.index')->with('error', $e->getMessage());

        } 

        return redirect()->route('admin.orders.index')->with('success', 'Produkta daudzums veiksmīgi nomainīts!');
    }

    public function deleteProduct(Order $order, OrderedProduct $orderedProduct) {

        if (!$order->orderedProducts()->where('id', $orderedProduct->id)->exists()) {
            abort(404);
        } 

        try {

            DB::transaction(function () use ($order, $orderedProduct) {

                $order = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();
                $orderedProduct = OrderedProduct::where('id', $orderedProduct->id)->where('order_id', $order->id)->lockForUpdate()->firstOrFail();
                $product = Product::where('id', $orderedProduct->product->id)->lockForUpdate()->firstOrFail();

                if ($order->status === 'delivered' || $order->status === 'cancelled') {
                    throw new \RuntimeException('Šis pasūtījums ir piegādāts vai atcelts! Dzēst preci šajā pasūtījuma nav iespējams!');
                }

                if ($order->orderedProducts()->count() === 1) {
                    throw new \RuntimeException('Nav iespējams dzest pēdējo prēci pasūtījumā!');
                }
              
                $product->increment('stock_quantity', $orderedProduct->product_count);
                $orderedProduct->delete();
              
            });

        } catch (\RuntimeException $e) {

            return redirect()->route('admin.orders.index')->with('error', $e->getMessage());

        } 

        return redirect()->route('admin.orders.index')->with('success', 'Produkts ir veiksmīgi nodzests!');

    }

    public function addProduct(Order $order, Request $request) {

        // Parbaudam vai ir saņemts id un vai produkts ar tadu id eksistē

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Metode find meklē produktu pēc tā id. Tā ir atrāka par metodi first

        $product = Product::findOrFail($request->product_id);

        try {

            DB::transaction(function () use ($order, $product) {

                $order = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();
                $product = Product::where('id', $product->id)->lockForUpdate()->firstOrFail();

                // Ja pasūtījums ir piegādāts vai atcelts, tam nevar pievienot preces

                if ($order->status === 'delivered' || $order->status === 'cancelled') {
                    throw new \RuntimeException('Šis pasūtījums ir piegādāts vai atcelts! Pievienot preci nav iespējams!');
                }

                if ($product->stock_quantity < 1) {
                    throw new \RuntimeException('Nepietiekams produkta daudzums noliktavā! Pievienot preci nav iespējams!');
                }

                // Parbaudam vai pievienojama prece jau ir pasūtījumā. Ja ir tad ta daudzums tiek palielināts, 
                // ja nav tad tiek veidota jauna pasūtījuma pozicija

                $orderedProduct = $order->orderedProducts()->where('product_id', $product->id)->lockForUpdate()->first();

                if ($orderedProduct) {

                    $orderedProduct->increment('product_count', 1);

                    $product->decrement('stock_quantity', 1);

                } else {

                    $orderedProduct = OrderedProduct::create([
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'product_count' => 1,
                        'unit_price_at_purchase' => $product->unit_price,
                    ]);

                    $product->decrement('stock_quantity', 1);

                }
              
            });

        } catch (\RuntimeException $e) {

            return redirect()->route('admin.orders.index')->with('error', $e->getMessage());

        } 

        return redirect()->route('admin.orders.index')->with('success', 'Produkts ir veiksmīgi pievienots!');

    }

}


