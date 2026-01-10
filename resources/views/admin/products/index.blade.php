@extends('admin.dashboard')

@section('content')

<style>
    :root{
        --brand:#151B54;
        --brand-dark:#0f1640;
        --muted:#6b7280;
        --soft-border:rgba(15,23,42,.08);
    }

    .page-title{
        font-weight:800;
        color:var(--brand);
        letter-spacing:.3px;
    }

    .table-card{
        border-radius:1rem;
        background:#fff;
        padding:1rem 1.25rem;
        box-shadow:0 6px 16px rgba(15,23,42,.05);
        border:1px solid var(--soft-border);
    }

    /* Badge kategori */
    .cat-badge{
        background: rgba(21,27,84,.12);
        color: var(--brand);
        font-weight:700;
        border-radius:.5rem;
        padding:.35rem .6rem;
        font-size:.75rem;
        text-transform: uppercase;
    }

    /* Stok per ukuran */
    .stock-badge{
        background:#f8f9fa;
        border:1px solid var(--soft-border);
        font-size:.75rem;
        font-weight:600;
        border-radius:.4rem;
        padding:.25rem .5rem;
        color:#000;
    }

    /* Tombol */
    .btn-brand{
        background:var(--brand) !important;
        border-color:var(--brand) !important;
        color:#fff !important;
        font-weight:600;
    }
    .btn-brand:hover{
        background:var(--brand-dark) !important;
    }

    .btn-outline-brand{
        border-color:var(--brand) !important;
        color:var(--brand) !important;
        font-weight:600;
    }
    .btn-outline-brand:hover{
        background:var(--brand) !important;
        color:white !important;
    }

    /* Action buttons */
    .btn-action{
        font-size:.8rem;
        padding:.35rem .55rem;
        border-radius:.4rem;
    }

</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Manajemen Produk</h1>

    <a href="{{ route('admin.products.create') }}" class="btn btn-brand">
        <i class="bi bi-plus-circle"></i> Tambah Produk Baru
    </a>
</div>

{{-- Alert sukses --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="table-card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:90px;">Gambar</th>
                    <th>Nama Produk</th>
                    <th style="width:130px;">Kategori</th>
                    <th style="width:140px;">Harga</th>
                    <th style="width:220px;">Stok</th>
                    <th style="width:160px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $p)
                <tr>
                    {{-- Gambar --}}
                    <td>
                        @if($p->image)
                            <img src="{{ asset('product_images/'.$p->image) }}"
                                 class="img-thumbnail rounded"
                                 style="width:70px; height:70px; object-fit:cover;">
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>

                    {{-- Nama + slug --}}
                    <td>
                        <div class="fw-bold">{{ $p->name }}</div>
                        <small class="text-muted">{{ $p->slug }}</small>
                    </td>

                    {{-- Kategori --}}
                    <td>
                        <span class="cat-badge">
                            {{ $p->category ?? '-' }}
                        </span>
                    </td>

                    {{-- Harga --}}
                    <td class="fw-semibold">
                        Rp {{ number_format($p->price, 0, ',', '.') }}
                    </td>

                    {{-- Stok per ukuran --}}
                    <td>
                        @if($p->variants && $p->variants->count() > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($p->variants as $v)
                                    <span class="stock-badge">
                                        {{ $v->size }} : {{ $v->stock }}
                                    </span>
                                @endforeach
                            </div>
                            <small class="text-muted mt-1 d-block">
                                Total: <strong>{{ $p->variants->sum('stock') }}</strong>
                            </small>
                        @else
                            <span class="text-muted small">Belum ada stok ukuran</span>
                        @endif
                    </td>

                    {{-- Tombol aksi --}}
                    <td>
                        <a href="{{ route('admin.products.edit', $p->id) }}"
                           class="btn btn-warning btn-action">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>

                        <form action="{{ route('admin.products.destroy', $p->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin hapus?')"
                                    class="btn btn-danger btn-action">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Belum ada produk di database.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection
