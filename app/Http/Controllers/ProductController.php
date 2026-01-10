<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // HALAMAN KATALOG
    public function index(Request $request)
    {
        $query = Product::query();

        //Search dari navbar/home - cari di nama ATAU kategori
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('category', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter kategori dari home/sidebar
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Sort
        $sort = $request->get('sort', 'default');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        // List kategori unik untuk sidebar
        $categories = Product::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        // Pagination + keep query string
        $products = $query->paginate(9)->withQueryString();

        //view katalog.blade.php
        return view('katalog', compact('products', 'categories', 'sort'));
    }

    // HALAMAN DETAIL (pakai slug)
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        //Produk terkait = kategori sama, beda id
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->inRandomOrder() 
            ->take(4)
            ->get();

        //kirim relatedProducts ke view detail
        return view('detail', compact('product', 'relatedProducts'));
    }
}
