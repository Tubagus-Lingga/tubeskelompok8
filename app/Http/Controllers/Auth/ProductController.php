<?php

namespace App\Http\Controllers;

use App\Models\Product; // Pastikan Anda punya Model Product
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk (Katalog).
     * Sesuai dengan Route: Route::get('/katalog', [ProductController::class, 'index'])->name('katalog');
     */
    public function index()
    {
        // Contoh: Mengambil semua produk dari database
        $products = Product::all(); 

        // Tampilkan view 'katalog.blade.php' dan kirim data produk
        return view('katalog', compact('products'));
    }

    /**
     * Menampilkan detail produk tunggal.
     * Sesuai dengan Route: Route::get('/detail/{slug}', [ProductController::class, 'show'])->name('detail');
     */
    public function show(string $slug)
    {
        // Contoh: Mencari produk berdasarkan slug
        $product = Product::where('slug', $slug)->firstOrFail(); 

        // Tampilkan view 'detail.blade.php'
        return view('detail', compact('product'));
    }
}
