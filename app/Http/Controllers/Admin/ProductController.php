<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // LIST PRODUK
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('admin.products.create');
    }

    // SIMPAN PRODUK
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filename = null;

        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('product_images'), $filename);
        }

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // generate slug otomatis
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $filename,
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    // FORM EDIT
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // UPDATE PRODUK
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filename = $product->image;

        if ($request->hasFile('image')) {
            // hapus gambar lama jika ada
            if ($filename && file_exists(public_path('product_images/' . $filename))) {
                unlink(public_path('product_images/' . $filename));
            }

            $filename = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('product_images'), $filename);
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // update slug
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $filename,
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diupdate!');
    }

    // HAPUS PRODUK
    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path('product_images/'.$product->image))) {
            unlink(public_path('product_images/'.$product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }
}
