@extends('admin.dashboard')

@section('content')
<h3 class="mb-4">Edit Produk</h3>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Nama Produk</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Harga</label>
        <input type="number" name="price" id="price" class="form-control" value="{{ $product->price }}" required>
    </div>

    <div class="mb-3">
        <label for="stock" class="form-label">Stok</label>
        <input type="number" name="stock" id="stock" class="form-control" value="{{ $product->stock }}" required>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Gambar Produk</label>
        <input type="file" name="image" id="image" class="form-control">
        @if($product->image)
            <img src="{{ asset('product_images/'.$product->image) }}" width="100" class="mt-2">
        @endif
    </div>

    <button type="submit" class="btn btn-success">Update Produk</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
