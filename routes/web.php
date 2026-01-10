<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

use App\Http\Controllers\User\OrderHistoryController;

// ==========================
// 1) HOME & PRODUK (PUBLIC)
// ==========================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [ProductController::class, 'index'])->name('katalog');
Route::get('/detail/{slug}', [ProductController::class, 'show'])->name('detail');


// ==========================
// 2) AUTH (LOGIN / REGISTER)
// ==========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ==========================
// 3) USER (AUTH)
// ==========================
Route::middleware('auth')->group(function () {

    // Keranjang localStorage → klik keranjang masuk checkout
    Route::get('/cart', fn () => redirect()->route('checkout.index'))
        ->name('cart.index');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.process');
    Route::get('/checkout/pay/{order}', [CheckoutController::class, 'pay'])->name('checkout.pay');

    Route::get('/checkout/payments/{payment}/status', [PaymentController::class, 'status'])
        ->name('checkout.payments.status');

    Route::post('/checkout/payments/{payment}/success', [PaymentController::class, 'success'])
        ->name('checkout.payments.success');

    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])
        ->name('checkout.success');

    // Riwayat pesanan user
    Route::get('/riwayat-pesanan', [OrderHistoryController::class, 'index'])
        ->name('orders.history');

    Route::get('/riwayat-pesanan/{order}', [OrderHistoryController::class, 'show'])
        ->name('orders.history.show');
});


// ==========================
// 4) ADMIN ROUTES (AUTH + ADMIN)
// ==========================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        Route::resource('products', AdminProductController::class);

        Route::get('/orders', [AdminOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])
            ->name('orders.show');

        Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.status');
    });
