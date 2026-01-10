<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // LIST PRODUK
    public function index()
    {
        // load variants biar admin bisa lihat stok per size kalau perlu
        $products = Product::with('variants')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('admin.products.create');
    }

    // SIMPAN PRODUK + VARIANTS
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|integer|min:1',
            'category'    => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // ✅ variants wajib
            'variants'            => 'required|array|min:1',
            'variants.*.size'     => 'required|string|max:10',
            'variants.*.stock'    => 'required|integer|min:0',
        ]);

        // generate slug otomatis
        $validated['slug'] = Str::slug($validated['name']);

        // upload image
        if ($request->hasFile('image')) {
            $filename = time() . '_' . preg_replace('/\s+/', '_', $request->image->getClientOriginalName());
            $request->image->move(public_path('product_images'), $filename);
            $validated['image'] = $filename;
        } else {
            $validated['image'] = null;
        }

        // (opsional) hitung total stock dari semua variants
        $totalStock = collect($validated['variants'])->sum('stock');

        // simpan product dulu
        $product = Product::create([
            'name'        => $validated['name'],
            'slug'        => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'category'    => $validated['category'],
            'image'       => $validated['image'],
            'stock'       => $totalStock, // ✅ total stok (opsional)
        ]);

        // simpan variants
        foreach ($validated['variants'] as $v) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size'       => strtoupper($v['size']),
                'stock'      => (int) $v['stock'],
            ]);
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan + stok per ukuran!');
    }

    // FORM EDIT
    public function edit(Product $product)
    {
        $product->load('variants');
        return view('admin.products.edit', compact('product'));
    }

    // UPDATE PRODUK + VARIANTS
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|integer|min:1',
            'category'    => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // ✅ variants wajib juga saat update
            'variants'            => 'required|array|min:1',
            'variants.*.size'     => 'required|string|max:10',
            'variants.*.stock'    => 'required|integer|min:0',
        ]);

        // update slug
        $validated['slug'] = Str::slug($validated['name']);

        // kalau upload gambar baru
        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('product_images/' . $product->image))) {
                unlink(public_path('product_images/' . $product->image));
            }

            $filename = time() . '_' . preg_replace('/\s+/', '_', $request->image->getClientOriginalName());
            $request->image->move(public_path('product_images'), $filename);
            $validated['image'] = $filename;
        } else {
            $validated['image'] = $product->image;
        }

        // (opsional) total stock dari variants
        $totalStock = collect($validated['variants'])->sum('stock');

        // update product
        $product->update([
            'name'        => $validated['name'],
            'slug'        => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'category'    => $validated['category'],
            'image'       => $validated['image'],
            'stock'       => $totalStock, // ✅ total stok (opsional)
        ]);

        // hapus variants lama lalu insert baru (cara paling simpel & aman)
        ProductVariant::where('product_id', $product->id)->delete();

        foreach ($validated['variants'] as $v) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size'       => strtoupper($v['size']),
                'stock'      => (int) $v['stock'],
            ]);
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diupdate + stok ukuran diperbarui!');
    }

    // HAPUS PRODUK (sekalian variants)
    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path('product_images/'.$product->image))) {
            unlink(public_path('product_images/'.$product->image));
        }

        // hapus variants dulu biar bersih
        ProductVariant::where('product_id', $product->id)->delete();

        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }
}
