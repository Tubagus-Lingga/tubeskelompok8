@extends('admin.dashboard')

@section('content')
<h1 class="h3 mb-4 fw-bold" style="color: var(--brand-color);">Dashboard Administrator</h1>

<div class="row g-4">
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm border-start border-5 border-primary">
            <div class="card-body">
                <div>Total Produk</div>
                <div class="h5 fw-bold">{{ $totalProduk ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm border-start border-5 border-success">
            <div class="card-body">
                <div>Pesanan Baru</div>
                <div class="h5 fw-bold">{{ $pesananBaru ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm border-start border-5 border-warning">
            <div class="card-body">
                <div>Pengguna Terdaftar</div>
                <div class="h5 fw-bold">{{ $totalUser ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
