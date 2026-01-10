<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (homepage) dan mengambil data produk.
     */
    public function index()
    {
        $featuredProducts = Product::query()
            // ✅ hanya produk yang punya minimal 1 varian stok > 0
            ->whereHas('variants', function ($q) {
                $q->where('stock', '>', 0);
            })
            // ✅ load variants untuk data-variants di blade
            ->with(['variants' => function ($q) {
                $q->select('id', 'product_id', 'size', 'stock');
            }])
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('featuredProducts'));
    }
}
