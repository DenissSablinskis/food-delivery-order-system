<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\StoreProductRequest;

class AdminProductController extends Controller
{
    public function index() {
        $products = Product::all();
        return view('pages.admin.products.index', compact('products'));
    }
    
    public function edit(Product $product) {
        return view('pages.admin.products.edit', compact('product'));
    }

    public function update(Product $product, UpdateProductRequest $request) {

        // Ja ir augšupielādēts jauns attēls, saglabā to un atjaunina produkta attēla ceļu
        
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public'); // Saglabā attēlu "storage/app/public/products" direktorijā, $imagePath saturēs ceļu, piemēram, "products/attels.jpg"
            $data['image'] = 'storage/' . $imagePath;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produkts veiksmīgi atjaunināts!');
    }

    public function destroy(Product $product) {

        if ($product->orderedProducts()->exists()) {
            return redirect()->route('admin.products.index')->with('error', 'Produktu nevar dzēst, jo tas ir saistīts ar pasūtījumiem!');
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produkts veiksmīgi dzēsts!');
    }

    public function create() {
        return view('pages.admin.products.create');
    }

    public function store(StoreProductRequest $request) {
     
        $data = $request->validated();

        $imagePath = $request->file('image')->store('products', 'public');
        $data['image'] = 'storage/' . $imagePath;
        
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produkts veiksmīgi izveidots!');
    }

}
