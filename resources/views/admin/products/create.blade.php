@extends('admin.dashboard')

@section('content')

<style>

    :root{
        --brand:#151B54;
        --brand-dark:#0f1640;
        --muted:#6b7280;
        --bg:#f6f7fb;
        --border-soft: rgba(15,23,42,.08);
        --card-bg:#ffffff;
    }

    *{
        font-family:'Inter',sans-serif !important;
    }

    .page-title{
        font-weight:800;
        color:var(--brand);
        letter-spacing:.3px;
        margin-bottom:1.5rem;
    }

    .form-card{
        background:var(--card-bg);
        border-radius:1rem;
        border:1px solid var(--border-soft);
        padding:2rem;
        box-shadow:0 4px 14px rgba(15,23,42,.06);
        transition:.2s ease;
    }
    .form-card:hover{
        box-shadow:0 10px 26px rgba(15,23,42,.12);
    }

    label.form-label{
        font-weight:600;
        color:#0f172a;
    }

    .form-control, .form-select{
        border-radius:.65rem !important;
        border:1px solid var(--border-soft);
        padding:.65rem .9rem;
        font-size:.95rem;
    }

    .variant-box{
        background:#f8f9ff;
        border:1px solid rgba(15,23,42,.08);
        padding:1rem;
        border-radius:.8rem;
    }

    .btn-brand{
        background:var(--brand) !important;
        border-color:var(--brand) !important;
        color:white !important;
        font-weight:600;
        border-radius:.6rem;
    }
    .btn-brand:hover{
        background:var(--brand-dark) !important;
    }

    .btn-outline-brand{
        border:1.5px solid var(--brand) !important;
        color:var(--brand) !important;
        border-radius:.6rem;
        font-weight:600;
    }
    .btn-outline-brand:hover{
        background:var(--brand) !important;
        color:white !important;
    }

    .remove-variant{
        border-radius:.5rem !important;
        font-weight:600;
    }

</style>

<h1 class="page-title">Tambah Produk Baru</h1>

<div class="form-card">

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- NAMA PRODUK --}}
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name') }}" required>
        </div>

        {{-- DESKRIPSI --}}
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
        </div>

        {{-- HARGA --}}
        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="price" min="1"
                   class="form-control" value="{{ old('price') }}" required>
        </div>

        {{-- VARIANTS --}}
        <div class="mb-3">
            <label class="form-label">Stok per Ukuran</label>

            <div class="variant-box">
                <div id="variantsWrapper" class="d-flex flex-column gap-2">

                    @php
                        $oldVariants = old('variants');
                        $defaults = [
                            ['size'=>'S','stock'=>0],
                            ['size'=>'M','stock'=>0],
                            ['size'=>'L','stock'=>0],
                            ['size'=>'XL','stock'=>0],
                        ];
                        $variantsToShow = is_array($oldVariants) ? $oldVariants : $defaults;
                    @endphp

                    @foreach($variantsToShow as $i => $v)
                        <div class="variant-row row g-2 align-items-center">
                            <div class="col-4 col-md-3">
                                <input type="text"
                                       name="variants[{{ $i }}][size]"
                                       class="form-control"
                                       placeholder="Ukuran"
                                       value="{{ $v['size'] }}"
                                       required>
                            </div>

                            <div class="col-6 col-md-3">
                                <input type="number"
                                       name="variants[{{ $i }}][stock]"
                                       class="form-control"
                                       min="0"
                                       value="{{ $v['stock'] }}"
                                       required>
                            </div>

                            <div class="col-2 col-md-2">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-variant">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach

                </div>

                <button type="button"
                        id="addVariantBtn"
                        class="btn btn-outline-brand btn-sm mt-3">
                    + Tambah Ukuran
                </button>

                <small class="text-muted d-block mt-2">
                    Minimal 1 ukuran harus ada.
                </small>
            </div>
        </div>

        {{-- KATEGORI --}}
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="kaos"   {{ old('category')=='kaos' ? 'selected':'' }}>Kaos</option>
                <option value="hoodie" {{ old('category')=='hoodie' ? 'selected':'' }}>Hoodie</option>
                <option value="celana" {{ old('category')=='celana' ? 'selected':'' }}>Celana</option>
            </select>
        </div>

        {{-- GAMBAR --}}
        <div class="mb-4">
            <label class="form-label">Gambar Produk</label>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>

        <button class="btn btn-brand">Simpan Produk</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-brand ms-2">Kembali</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('variantsWrapper');
    const addBtn = document.getElementById('addVariantBtn');

    function reindex(){
        wrapper.querySelectorAll('.variant-row').forEach((row, i) => {
            row.querySelector('input[name*="[size]"]').name  = `variants[${i}][size]`;
            row.querySelector('input[name*="[stock]"]').name = `variants[${i}][stock]`;
        });
    }

    function attachRemove(el){
        el.addEventListener('click', () => {
            const rows = wrapper.querySelectorAll('.variant-row');
            if(rows.length <= 1){
                alert("Minimal harus ada 1 ukuran.");
                return;
            }
            el.closest('.variant-row').remove();
            reindex();
        });
    }

    wrapper.querySelectorAll('.remove-variant').forEach(attachRemove);

    addBtn.addEventListener('click', () => {
        const idx = wrapper.querySelectorAll('.variant-row').length;

        const div = document.createElement('div');
        div.className = "variant-row row g-2 align-items-center";
        div.innerHTML = `
            <div class="col-4 col-md-3">
                <input type="text" class="form-control" name="variants[${idx}][size]" placeholder="Ukuran" required>
            </div>
            <div class="col-6 col-md-3">
                <input type="number" class="form-control" name="variants[${idx}][stock]" placeholder="Stok" min="0" value="0" required>
            </div>
            <div class="col-2 col-md-2">
                <button type="button" class="btn btn-outline-danger btn-sm remove-variant">Hapus</button>
            </div>
        `;

        wrapper.appendChild(div);
        attachRemove(div.querySelector('.remove-variant'));
    });
});
</script>

@endsection
