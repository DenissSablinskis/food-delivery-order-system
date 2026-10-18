<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Models\Order;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminOrderController;

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

// Lietotāja pasūtījumu saraksta maršruts

Route::get('/orders', [OrderController::class, 'index'])->middleware('auth')->name('orders.index');

// Lietotāja profila maršruts

Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth')->name('profile.show');

// Profila rēdiģešanas maršruti 

Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');

Route::put('/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->middleware('auth')->name('profile.update-password');

// Pasūtījumu apskates maršruts

Route::get('/orders/{order}', function (Order $order) {
    return view('pages.orders.show', compact('order'));
})->middleware(['auth', 'order.owner'])->name('orders.show');

// Pasūtījumu rēķina lejupielādes maršruts

Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice')->middleware(['auth', 'order.owner']);

// Administratora paneļa maršruti

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('/orders/{order}/address', [AdminOrderController::class, 'updateAddress'])->name('orders.update-address');
        Route::patch('/orders/{order}/products/{orderedProduct}/increase', [AdminOrderController::class, 'increaseProductQuantity'])->name('orders.increase-product-quantity');
        Route::patch('/orders/{order}/products/{orderedProduct}/decrease', [AdminOrderController::class, 'decreaseProductQuantity'])->name('orders.decrease-product-quantity');
        Route::delete('/orders/{order}/products/{orderedProduct}', [AdminOrderController::class, 'deleteProduct'])->name('orders.delete-product');
        Route::post('/orders/{order}/products', [AdminOrderController::class, 'addProduct'])->name('orders.add-product');
        
    });

