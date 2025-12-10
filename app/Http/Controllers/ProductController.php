<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)
            ->latest()
            ->paginate(12);

        return view('katalog', compact('products'));
    }

    public function show($id)
    {
        // ✅ Produk stok 0 otomatis tidak bisa diakses (akan 404)
        $product = Product::where('id', $id)
            ->where('stock', '>', 0)
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}
