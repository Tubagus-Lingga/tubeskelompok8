<?php

<<<<<<< HEAD
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController; 
use App\Http\Controllers\HomeController; // Contoh Controller baru
use App\Http\Controllers\ProductController; // Contoh Controller baru
use Illuminate\Support\Facades\Route;
=======
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController; 
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // YANG BENAR
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

<<<<<<< HEAD
// ===================================================================
// 1. ROUTE UTAMA UNTUK PELANGGAN/GUEST (E-COMMERCE)
// *Semua logika view ditaruh di Controller*
// ===================================================================

// Halaman Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Katalog dan Detail Produk
Route::get('/katalog', [ProductController::class, 'index'])->name('katalog'); 
Route::get('/detail/{slug}', [ProductController::class, 'show'])->name('detail'); 

// Halaman Keranjang & Checkout (Bisa dikelompokkan jika Anda mau)
Route::group([], function () {
    Route::get('/cart', function () { return view('cart'); })->name('cart.index');
    Route::get('/checkout', function () { return view('checkout'); })->name('checkout.index');
});

// ---

// ===================================================================
// 2. ROUTE OTENTIKASI (LOGIN & REGISTER)
// *Gunakan Controller untuk GET dan POST*
// ===================================================================

Route::group(['middleware' => ['guest']], function () {
    // Menampilkan Form Login (GET)
    Route::get('/login', [LoginController::class, 'create'])->name('login');

    // Memproses Form Login (POST)
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit'); 

    // Menampilkan Form Register (GET)
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

    // Memproses Form Register (POST)
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store'); 
});

// Route Logout (Biasanya hanya dapat diakses oleh user yang sudah login)
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout'); 

// ---

// ===================================================================
// 3. ROUTE KHUSUS ADMIN (Dilindungi Middleware 'admin')
// ===================================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // /admin/dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('dashboard');

    // Contoh: Route Resource untuk Produk (CRUD)
    // Route::resource('products', ProductManagementController::class);
});
=======
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
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
