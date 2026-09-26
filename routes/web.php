<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

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

// Checkout lapas maršruts

Route::get('/checkout', [ProductController::class, 'checkout'])->name('checkout.index');