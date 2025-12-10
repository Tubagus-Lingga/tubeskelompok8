<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController; 
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // YANG BENAR

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. ROUTE HOME & PRODUK
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [ProductController::class, 'index'])->name('katalog'); 
Route::get('/detail/{slug}', [ProductController::class, 'show'])->name('detail'); 

Route::get('/cart', fn() => view('cart'))->name('cart.index');
Route::get('/checkout', fn() => view('checkout'))->name('checkout.index');

// 2. ROUTE AUTH (LOGIN/REGISTER)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

// LOGOUT (HANYA USER LOGIN)
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// 3. ROUTE KHUSUS ADMIN
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        

        // CRUD PRODUK
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    });
