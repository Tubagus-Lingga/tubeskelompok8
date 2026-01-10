@extends('admin.dashboard')

@section('content')

<style>

    :root{
        --brand:#151B54;
        --brand-dark:#0f1640;
        --muted:#6b7280;
        --border-soft: rgba(15,23,42,.08);
        --card-bg:#ffffff;
    }

    *{
        font-family: 'Inter', sans-serif !important;
    }

    .page-title{
        font-weight:800;
        color:var(--brand);
        letter-spacing:.3px;
        margin-bottom:1.2rem;
    }

    .info-card, .item-card{
        background:var(--card-bg);
        border-radius:1rem;
        border:1px solid var(--border-soft);
        box-shadow:0 4px 14px rgba(15,23,42,.05);
    }

    .info-card:hover, .item-card:hover{
        box-shadow:0 8px 22px rgba(15,23,42,.12);
        transition:.2s ease;
    }

    .section-title{
        font-weight:800;
        font-size:1rem;
        color:var(--brand-dark);
        margin-bottom:1rem;
    }

    .info-line strong{
        color:var(--black);
    }

    .badge-status{
        padding:.45rem .65rem;
        border-radius:.55rem;
        font-size:.75rem;
        font-weight:700;
        color:#fff !important;
    }

    .select-brand{
        border-radius:.6rem;
        border:1px solid var(--border-soft);
        padding:.6rem .75rem;
        font-weight:500;
    }

    .btn-brand{
        background:var(--brand) !important;
        color:white !important;
        border:none !important;
        font-weight:600;
        border-radius:.6rem;
    }
    .btn-brand:hover{
        background:var(--brand-dark) !important;
    }

    .btn-outline-brand{
        border:1.5px solid var(--brand) !important;
        color:var(--brand) !important;
        font-weight:600 !important;
        border-radius:.6rem;
    }
    .btn-outline-brand:hover{
        background:var(--brand) !important;
        color:white !important;
    }

    table thead th{
        background:#f2f4f8 !important;
        color:var(--brand);
        font-weight:700;
    }

    table tbody tr:hover{
        background:#f7f9ff;
    }

</style>

<h1 class="page-title">Detail Pesanan</h1>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">

    {{-- INFO PESANAN --}}
    <div class="col-lg-5">
        <div class="info-card p-4 mb-3">

            <div class="section-title">Informasi Pesanan</div>

            <p class="info-line mb-1"><strong>Invoice:</strong> {{ $order->code }}</p>
            <p class="info-line mb-1"><strong>Pemesan:</strong> {{ $order->user->name ?? '-' }}</p>
            <p class="info-line mb-1"><strong>Email:</strong> {{ $order->user->email ?? '-' }}</p>
            <p class="info-line mb-1"><strong>No. Telp:</strong> {{ $order->telp ?? '-' }}</p>
            
            <p class="info-line mb-1"><strong>Alamat Lengkap:</strong></p>
            <p class="mb-1 ms-3">
                {{ $order->alamat ?? '-' }}<br>
                {{ $order->district ?? '-' }}, {{ $order->city ?? '-' }} {{ $order->postal_code ?? '-' }}
            </p>


            {{-- STATUS ORDER --}}
            <p class="mb-1">
                <strong>Status Order:</strong><br>
                <span class="badge-status 
                    @if($order->status=='paid') bg-success 
                    @elseif($order->status=='pending_payment') bg-warning text-dark 
                    @else bg-secondary @endif">
                    {{ ucfirst(str_replace('_',' ', $order->status)) }}
                </span>
            </p>

            {{-- STATUS BAYAR --}}
            <p class="mb-1">
                <strong>Status Bayar:</strong><br>
                <span class="badge-status 
                    @if(optional($order->payment)->status=='success') bg-success 
                    @elseif(optional($order->payment)->status=='pending') bg-warning text-dark 
                    @else bg-secondary @endif">
                    {{ ucfirst(optional($order->payment)->status ?? '-') }}
                </span>
            </p>

            <p class="mb-1">
                <strong>Total:</strong> Rp{{ number_format($order->total_amount,0,',','.') }}</span>
            </p>

            <p class="mb-1">
                <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}
            </p>
        </div>

        {{-- UPDATE STATUS --}}
        <div class="info-card p-4">
            <div class="section-title">Update Status Penanganan</div>

            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <select name="handling_status" class="form-select select-brand mb-3">
                    <option value="new" {{ $order->handling_status=='new' ? 'selected' : '' }}>Pesanan Baru</option>
                    <option value="processing" {{ $order->handling_status=='processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="shipped" {{ $order->handling_status=='shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="done" {{ $order->handling_status=='done' ? 'selected' : '' }}>Selesai</option>
                </select>

                <button class="btn btn-brand w-100 py-2">Simpan Status</button>
            </form>
        </div>
    </div>

    {{-- LIST PRODUK --}}
    <div class="col-lg-7">
        <div class="item-card p-4">

            <div class="section-title">Produk yang Dibeli</div>

            <div class="table-responsive">
                <table class="table align-middle table-bordered">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Ukuran</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $it)
                        <tr>
                            <td class="fw-semibold">{{ $it->product->name ?? $it->name }}</td>

                            <td class="text-center">
                                @if($it->size)
                                    <span class="badge bg-light text-dark border">{{ $it->size }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <td class="text-center">{{ $it->qty }}</td>

                            <td class="text-center">
                                Rp{{ number_format($it->price,0,',','.') }}
                            </td>

                            <td class="text-center fw-semibold">
                                Rp{{ number_format($it->subtotal,0,',','.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3 fw-bold" style="font-size:1.2rem;">
                TOTAL: Rp{{ number_format($order->total_amount,0,',','.') }}
            </div>

        </div>
    </div>
</div>

<a href="{{ route('admin.orders.index') }}" class="btn btn-outline-brand mt-4">
    ← Kembali
</a>

@endsection
