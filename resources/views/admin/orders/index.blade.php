@extends('admin.dashboard')

@section('content')

<style>
    :root{
        --brand:#151B54;
        --brand-dark:#0f1640;
    }

    body, h1, h2, h3, h4, h5, h6 {
        font-family: 'Inter', sans-serif !important;
    }

    .page-header {
        font-size: 2.4rem;
        font-weight: 800;
        color: var(--brand);
        letter-spacing: .3px;
        margin-bottom: 2rem;
    }

    .order-card {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem 1.75rem;
        border: 1px solid rgba(21,27,84,0.12);
        box-shadow: 0 6px 20px rgba(21,27,84,0.08);
        transition: .15s ease;
        margin-bottom: 1.5rem;
    }
    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 26px rgba(21,27,84,0.13);
    }

    .order-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--brand);
    }
    .order-date {
        font-size: .85rem;
        color: #6b7280;
    }

    .order-info-grid {
        display: grid;
        grid-template-columns: repeat(5, 180px);
        gap: 1rem;
        margin-top: 1rem;
    }

    @media(max-width: 1100px){
        .order-info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .info-label {
        font-size: .8rem;
        color: #6b7280;
        margin-bottom: .2rem;
    }

    .info-value {
        font-size: .95rem;
        font-weight: 700;
    }

    .item-list {
        border-top: 1px solid rgba(0,0,0,.06);
        margin-top: 1rem;
        padding-top: .8rem;
    }

    .item-line {
        font-size: .9rem;
        margin-bottom: .25rem;
        color:#444;
    }

    .badge-size {
        background: #eef0f4;
        border: 1px solid rgba(15,23,42,0.12);
        padding: .15rem .45rem;
        font-size: .75rem;
        border-radius: .35rem;
        margin-left: .3rem;
    }

    .btn-detail {
        background: var(--brand);
        color: #fff;
        border: none;
        padding: .45rem 1rem;
        border-radius: .5rem;
        font-weight: 600;
    }
    .btn-detail:hover {
        background: var(--brand-dark);
    }

    .badge {
        padding: .45rem .65rem;
        font-size: .75rem;
        font-weight: 600;
        border-radius: .45rem;
    }

    .badge-new {
        background: var(--brand);
        color: #fff;
    }

    .pagination {
        display: inline-flex;
        gap: 0;
        border-radius: 12px;
        overflow: hidden;
        background: #f0f2f8;
        padding: 4px;
        margin-bottom: 0;
    }

    .pagination .page-item {
        margin: 0 !important;
    }

    .pagination .page-link {
        border: none !important;
        background: transparent;
        color: #151B54;
        font-weight: 600;
        padding: 8px 14px;
        border-radius: 8px !important;
        min-width: 38px;
        text-align: center;
    }

    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 18px;
        padding-inline: 10px;
    }

    .pagination .page-item.active .page-link {
        background: #151B54 !important;
        color: #ffffff !important;
    }

    .pagination .page-item.disabled .page-link {
        color: #c0c4d3 !important;
    }
</style>

{{-- HEADER --}}
<h1 class="page-header">Pesanan Masuk</h1>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- STATUS MAPPING --}}
@php
    $orderBadge = [
        'pending_payment' => 'bg-warning text-dark',
        'paid'            => 'bg-success',
        'cancelled'       => 'bg-danger',
    ];

    $payBadge = [
        'pending' => 'bg-secondary',
        'success' => 'bg-success',
        'failed'  => 'bg-danger',
    ];

    $handleBadge = [
        'new'        => 'badge-new',
        'processing' => 'bg-warning text-dark',
        'shipped'    => 'bg-info text-dark',
        'done'       => 'bg-success',
    ];

    $handleLabel = [
        'new'        => 'Pesanan Baru',
        'processing' => 'Diproses',
        'shipped'    => 'Dikirim',
        'done'       => 'Selesai',
    ];
@endphp

{{-- ORDER LOOP --}}
@forelse($orders as $o)
    @php
        $st = $o->status;
        $ps = $o->payment->status ?? 'pending';
        $hs = $o->handling_status ?? 'new';
    @endphp

    <div class="order-card">

        {{-- ORDER HEADER --}}
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="order-title">{{ $o->code }}</div>
                <div class="order-date">{{ $o->created_at->format('d M Y H:i') }}</div>
            </div>

            <a href="{{ route('admin.orders.show', $o->id) }}" class="btn btn-detail">
                Detail
            </a>
        </div>

        {{-- GRID INFO --}}
        <div class="order-info-grid">

            <div>
                <div class="info-label">Pemesan</div>
                <div class="info-value" style="color:var(--brand);">
                    {{ $o->user->name ?? 'Guest' }}
                </div>
            </div>

            <div>
                <div class="info-label">Total</div>
                <div class="info-value">
                    Rp{{ number_format($o->total_amount,0,',','.') }}
                </div>
            </div>

            <div>
                <div class="info-label">Status Order</div>
                <span class="badge {{ $orderBadge[$st] ?? 'bg-dark' }}">
                    {{ ucfirst(str_replace('_',' ', $st)) }}
                </span>
            </div>

            <div>
                <div class="info-label">Status Bayar</div>
                <span class="badge {{ $payBadge[$ps] ?? 'bg-dark' }}">
                    {{ ucfirst($ps) }}
                </span>
            </div>

            <div>
                <div class="info-label">Penanganan</div>
                <span class="badge {{ $handleBadge[$hs] ?? 'bg-dark' }}">
                    {{ $handleLabel[$hs] ?? ucfirst($hs) }}
                </span>
            </div>

        </div>

        {{-- ITEM LIST --}}
        <div class="item-list">
            @foreach($o->items as $it)
                <div class="item-line">
                    • {{ $it->name }}
                    @if($it->size)
                        <span class="badge-size">Size {{ $it->size }}</span>
                    @endif
                    ({{ $it->qty }}x)
                </div>
            @endforeach
        </div>

    </div>

@empty
    <p class="text-center text-muted mt-3">Belum ada pesanan masuk.</p>
@endforelse

{{-- PAGINATION --}}
@if($orders->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection
