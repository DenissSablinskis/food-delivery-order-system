<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();
        return view('pages.products.index', compact('products'));
    }
    
     public function cart()
    {
        $products = Product::all();
        return view('pages.cart.index', compact('products'));
    }
}
