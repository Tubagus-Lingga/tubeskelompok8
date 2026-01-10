@extends('admin.dashboard')

@section('content')

<style>

    :root{
        --brand:#151B54;
        --brand-dark:#0f1640;
        --white:#ffffff;
        --black:#0f172a;
        --muted:#6b7280;
        --card-bg:#ffffff;
        --soft-border: rgba(15,23,42,.08);
    }

    *{
        font-family: 'Inter', sans-serif !important;
    }

    /* Judul halaman */
    .page-title{
        font-weight:800;
        color:var(--brand);
        letter-spacing:.3px;
        margin-bottom:1.5rem;
    }

    /* Card ringkasan */
    .summary-card{
        border:1px solid var(--soft-border);
        border-radius:1rem;
        background:var(--card-bg);
        box-shadow:0 4px 14px rgba(15,23,42,.05);
        transition:.15s ease;
    }
    .summary-card:hover{
        transform:translateY(-3px);
        box-shadow:0 10px 22px rgba(15,23,42,.10);
    }
    .summary-label{
        color:var(--muted);
        font-size:.9rem;
        font-weight:600;
    }
    .summary-value{
        font-size:1.7rem;
        font-weight:800;
        color:var(--black);
        margin-top:.25rem;
    }
    .summary-border{
        border-left:6px solid var(--brand) !important;
    }

    /* Latest orders table */
    .admin-card{
        border-radius:1rem;
        border:1px solid var(--soft-border);
        background:var(--card-bg);
        box-shadow:0 4px 14px rgba(15,23,42,.05);
    }

    .admin-card h5{
        font-weight:800;
        color:var(--brand);
    }

    table thead th{
        font-weight:800 !important;
        background:#f2f4f8 !important;
        color:var(--brand) !important;
        border-bottom:2px solid rgba(0,0,0,.06);
        font-size:.90rem;
    }

    table tbody tr{
        background:#fff;
        transition:.1s ease;
    }
    table tbody tr:hover{
        background:#f8f9ff;
    }

    /* Invoice */
    .invoice-link{
        font-weight:700;
        color:var(--brand);
        text-decoration:none;
    }
    .invoice-link:hover{
        text-decoration:underline;
    }

    .orderer-text{
        font-weight:550 !important;
        color:#111 !important;
    }

    .price-text{
        font-weight:550 !important;
        color:#111 !important;
    }

    /* Badges */
    .badge{
        padding:.45rem .65rem !important;
        font-weight:700 !important;
        border-radius:.55rem !important;
        font-size:.70rem !important;
        letter-spacing:.2px;
    }

    .btn-primary{
        background:var(--brand) !important;
        border-color:var(--brand) !important;
        font-weight:600;
        border-radius:.55rem;
        padding:.35rem .9rem;
    }
    .btn-primary:hover{
        background:var(--brand-dark) !important;
    }

    .btn-outline-primary{
        border-color:var(--brand) !important;
        color:var(--brand) !important;
        font-weight:600;
        border-radius:.55rem;
    }
    .btn-outline-primary:hover{
        background:var(--brand) !important;
        color:#fff !important;
    }
</style>

<h1 class="page-title">Dashboard Administrator</h1>

{{-- =============== RINGKASAN =============== --}}
<div class="row g-4 mb-4">

    <div class="col-lg-4 col-md-6">
        <div class="summary-card p-4 summary-border">
            <div class="summary-label">Total Produk</div>
            <div class="summary-value">{{ $totalProduk ?? 0 }}</div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="summary-card p-4 summary-border" style="border-left-color:#198754 !important;">
            <div class="summary-label">Pesanan Baru</div>
            <div class="summary-value">{{ $pesananBaru ?? 0 }}</div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="summary-card p-4 summary-border" style="border-left-color:#ffc107 !important;">
            <div class="summary-label">Pengguna Terdaftar</div>
            <div class="summary-value">{{ $totalUser ?? 0 }}</div>
        </div>
    </div>

</div>

{{-- =============== PESANAN TERBARU =============== --}}
<div class="admin-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Pesanan Terbaru</h5>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
            Lihat Semua →
        </a>
    </div>

    @if(isset($latestOrders) && $latestOrders->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#Invoice</th>
                        <th>Pemesan</th>
                        <th>Total</th>
                        <th>Status Order</th>
                        <th>Status Bayar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($latestOrders as $o)
                        <tr>
                            <td class>
                                <a class="invoice-link" href="{{ route('admin.orders.show', $o->id) }}">
                                    {{ $o->code }}
                                </a>
                            </td>

                            <td class="orderer-text">
                                {{ $o->user->name ?? '-' }}
                            </td>

                            {{-- HARGA TIDAK BOLD --}}
                            <td class="price-text">
                                Rp {{ number_format($o->total_amount,0,',','.') }}
                            </td>

                            {{-- Status Order --}}
                            <td>
                                <span class="badge 
                                    @if($o->status === 'paid') bg-success
                                    @elseif($o->status === 'pending_payment') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ ucfirst($o->status) }}
                                </span>
                            </td>

                            {{-- Status Pembayaran --}}
                            <td>
                                <span class="badge
                                    @if(optional($o->payment)->status === 'success') bg-success
                                    @elseif(optional($o->payment)->status === 'pending') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ optional($o->payment)->status ?? '-' }}
                                </span>
                            </td>

                            <td>{{ $o->created_at->format('d M Y H:i') }}</td>

                            <td>
                                <a href="{{ route('admin.orders.show', $o->id) }}"
                                   class="btn btn-sm btn-primary">
                                   Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @else
        <div class="text-muted small">Belum ada pesanan masuk.</div>
    @endif
</div>

@endsection
