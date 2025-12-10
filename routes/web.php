<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1) HOME & PRODUK
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [ProductController::class, 'index'])->name('katalog');
Route::get('/detail/{slug}', [ProductController::class, 'show'])->name('detail');

// CART (frontend localStorage)
Route::get('/cart', fn () => redirect()->route('checkout.index'))->name('cart.index');


// ✅ CHECKOUT UI (alamat + metode bayar) -> controller index
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

/*
|--------------------------------------------------------------------------
| CHECKOUT & PEMBAYARAN (Backend - Lingga)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // tombol "Konfirmasi Pembayaran" akan hit ini (buat order+payment pending)
    Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.process');

    // halaman QR + pengecekan pembayaran
    Route::get('/checkout/pay/{order}', [CheckoutController::class, 'pay'])->name('checkout.pay');

    // endpoint status utk polling (JS)
    Route::get('/checkout/payments/{payment}/status', [PaymentController::class, 'status'])->name('checkout.payments.status');

    // simulasi: tandai sukses (buat demo)
    Route::post('/checkout/payments/{payment}/success', [PaymentController::class, 'success'])->name('checkout.payments.success');

    // halaman sukses
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// 2) AUTH (LOGIN/REGISTER)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// 3) ADMIN
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    });
