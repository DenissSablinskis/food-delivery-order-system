<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Models\Order;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/home', function () {
    return view('pages.home');
})->name('home');

// Valodu parslegšanas maršruts

Route::post('/locale/switch/{locale}', function (Request $request, string $locale) {
    $allowedLocales = ['lv', 'en'];

    if (in_array($locale, $allowedLocales, true)) {
        $request->session()->put('locale', $locale);
    }

    return redirect()->back();
})->name('locale.switch');

// Autentifikācijas maršruti

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Produktu lapas maršruts

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Groza lapas maršruts

Route::get('/cart', [ProductController::class, 'cart'])->name('cart.index');

// Checkout lapas maršruti

Route::get('/checkout', [ProductController::class, 'checkout'])->name('checkout.index');

Route::post('/checkout', [ProductController::class, 'createOrder'])->middleware('auth')->name('checkout.store');

// Pasūtījumu apskates maršruts

Route::get('/orders/{order}', function (Order $order) {
    return view('pages.orders.show', compact('order'));
})->middleware(['auth', 'order.owner']);

// Pasūtījumu rēķina lejupielādes maršruts

Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice')->middleware(['auth', 'order.owner']);