@extends('layouts.app')

@section('title', 'ORDER DETAIL | VIBRANT')

@section('styles')
<style>
    /* PAGE HEADER */
    .page-title {
        font-weight: 900;
        font-size: 2rem;
        text-transform: uppercase;
        margin-bottom: 2rem;
        letter-spacing: -0.02em;
    }

    /* BRUTALIST CARD */
    .brutalist-card {
        background: #fff;
        border: 1px solid #000;
        box-shadow: 8px 8px 0 rgba(0,0,0,1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .section-title {
        font-weight: 900;
        font-size: 1.25rem;
        text-transform: uppercase;
        border-bottom: 2px solid #000;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .invoice-info {
        font-family: 'Courier New', Courier, monospace;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    /* STATUS BADGES */
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 800;
        border: 1px solid #000;
        letter-spacing: 0.05em;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .badge-paid { background: #000; color: #fff; }
    .badge-pending { background: #fff; color: #000; }
    .badge-failed { background: #000; color: #fff; text-decoration: line-through; }
    
    .badge-process { background: #fff; border: 1px dashed #000; color: #000; }
    .badge-shipped { background: #000; color: #fff; }
    .badge-done { background: #000; color: #fff; }

    /* ITEM ROW */
    .item-row {
        border-bottom: 1px solid #eee;
        padding: 1rem 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .item-row:last-child { border-bottom: none; }
    
    .item-name {
        font-weight: 800;
        text-transform: uppercase;
        font-size: 1rem;
    }
    .item-meta {
        font-size: 0.85rem;
        color: #666;
    }
    .item-price {
        font-weight: 900;
        font-size: 1.1rem;
    }

    /* TOTAL DISPLAY */
    .total-display {
        background: #000;
        color: #fff;
        padding: 1.5rem;
        text-align: right;
        margin-top: 1rem;
    }
    .total-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        font-weight: 700;
        opacity: 0.8;
    }
    .total-value {
        font-size: 2rem;
        font-weight: 900;
        display: block;
    }

    /* BUTTONS */
    .btn-brutal {
        display: inline-block;
        padding: 1rem 2rem;
        font-weight: 800;
        text-transform: uppercase;
        background: #000;
        color: #fff;
        border: 1px solid #000;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-brutal:hover {
        background: #fff;
        color: #000;
        transform: translate(-2px, -2px);
        box-shadow: 2px 2px 0 #000;
    }

    @media (max-width: 768px) {
        .item-row { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        .total-value { font-size: 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0">Order Detail</h2>
        <a href="{{ route('orders.history') }}" class="btn-brutal" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
            ← Back
        </a>
    </div>

    @php
        $ps = $order->payment->status ?? 'pending';
        $st = $order->status ?? 'pending_payment';
        $hs = $order->handling_status ?? 'new';
        $canPay = in_array($ps, ['pending','failed']) && $st === 'pending_payment';
    @endphp

    <div class="row">
        <div class="col-lg-8">
            <div class="brutalist-card">
                <div class="section-title">Purchased Items</div>
                <div class="items-list">
                    @foreach($order->items as $it)
                    <div class="item-row">
                        <div>
                            <div class="item-name">{{ $it->product->name ?? $it->name }}</div>
                            <div class="item-meta">
                                Size: <strong>{{ $it->size ?? '-' }}</strong> | 
                                {{ $it->qty }} x Rp{{ number_format($it->price, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="item-price">
                            Rp{{ number_format($it->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="brutalist-card">
                <div class="section-title">Shipping Address</div>
                <div class="invoice-info">
                    {{ $order->user->name ?? '-' }} ({{ $order->telp ?? '-' }})<br>
                    <span style="font-family: inherit; font-weight: 400; color: #333;">
                        {{ $order->alamat ?? '-' }}<br>
                        {{ $order->district ?? '-' }}, {{ $order->city ?? '-' }} {{ $order->postal_code ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="brutalist-card p-0 overflow-hidden">
                <div class="p-4">
                    <div class="section-title">Order Summary</div>
                    <div class="invoice-info">#{{ $order->code }}</div>
                    <div class="text-muted small mb-3">{{ $order->created_at->format('d M Y H:i') }}</div>

                    <div class="mb-2">
                        <span class="status-badge {{ $ps=='success' ? 'badge-paid' : ($ps=='failed' ? 'badge-failed' : 'badge-pending') }}">
                            Payment: {{ $ps }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="status-badge badge-process">
                            Status: {{ $st == 'pending_payment' ? 'UNPAID' : ($st=='paid'?'PAID':$st) }}
                        </span>
                    </div>
                    @if($hs != 'new')
                    <div class="mb-2">
                        <span class="status-badge badge-shipped">
                            Shipping: {{ $hs }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="total-display">
                    <span class="total-label">Total Amount</span>
                    <span class="total-value">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($canPay)
            <div class="mt-4">
                <a href="{{ route('checkout.pay', $order->id) }}" class="btn-brutal w-100 text-center">
                    Proceed to Payment
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
