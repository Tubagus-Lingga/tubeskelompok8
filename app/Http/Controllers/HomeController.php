<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Pastikan Anda mengimpor Model Product

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (homepage) dan mengambil data produk.
     */
    public function index()
    {
        // ----------------------------------------------------
        // LOGIKA UNTUK MENGAMBIL PRODUK UNGGULAN (FEATURED)
        // ----------------------------------------------------
        
        try {
            // Ambil produk yang kolom 'is_featured'-nya TRUE.
            $featuredProducts = Product::where('is_featured', true)
                                       ->latest() // Urutkan berdasarkan yang terbaru di-update
                                       ->take(4)  // Batasi hanya 4 produk
                                       ->get();
        } catch (\Exception $e) {
            // Fallback: Jika terjadi error database (misalnya, kolom belum ada),
            // tetap tampilkan 4 produk terbaru untuk mencegah error fatal.
            // Pastikan Anda telah menjalankan migrasi add_is_featured_to_products_table
            // untuk menghilangkan fallback ini.
            $featuredProducts = Product::latest()->take(4)->get();
            
            // Opsional: Mencatat error ke log Laravel
            // \Log::error("Error mengambil produk unggulan: " . $e->getMessage());
        }


        // Kirim data produk unggulan ke view 'home.blade.php'
        return view('home', [
            'featuredProducts' => $featuredProducts,
        ]);
    }
}