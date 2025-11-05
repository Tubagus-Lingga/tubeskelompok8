<?php

// routes/web.php

// Pastikan Anda mengimpor Controller yang diperlukan
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController; 
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================================================================
// 1. ROUTE UTAMA UNTUK PELANGGAN/GUEST (E-COMMERCE)
// ===================================================================

// Halaman Home (Route Utama)
Route::get('/', function () {
    return view('home'); 
})->name('home');

// Halaman Katalog (Asumsi menggunakan view katalog.blade.php)
Route::get('/katalog', function () {
    return view('katalog'); 
})->name('katalog');

// Halaman Detail Produk (Asumsi menggunakan view detail.blade.php)
Route::get('/detail/{id}', function ($id) {
    return view('detail'); 
})->name('detail');

// Halaman Keranjang Belanja
Route::get('/cart', function () {
    return view('cart'); 
})->name('cart.index');

// Halaman Checkout & Pembayaran
Route::get('/checkout', function () {
    return view('checkout'); 
})->name('checkout.index');


// ===================================================================
// 2. ROUTE OTENTIKASI (LOGIN & REGISTER)
// ===================================================================

// Menampilkan Form Login (GET)
Route::get('/login', function () {
    return view('login'); 
})->name('login');

// Memproses Form Login (POST) - FIXED
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit'); 

// Menampilkan Form Register (GET)
Route::get('/register', function () {
    return view('register'); 
})->name('register');

// Memproses Form Register (POST) - FIXED: Diberi nama 'register.store'
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store'); 


// ===================================================================
// 3. ROUTE KHUSUS ADMIN (Dilindungi Middleware 'admin')
// ===================================================================

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('admin.dashboard');

    // Tempatkan semua route manajemen (CRUD Produk, Pesanan, dll.) di sini
});
