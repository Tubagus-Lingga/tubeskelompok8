@extends('layouts.app')

@section('title', 'ORDER HISTORY | VIBRANT')

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

    /* HISTORY CARD */
    .history-card {
        background: #fff;
        border: 1px solid #000;
        box-shadow: 8px 8px 0 rgba(0,0,0,1); /* Brutalist shadow */
        padding: 0;
    }

    /* TABLE STYLES */
    .table-custom {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }
    .table-custom thead th {
        background: #000;
        color: #fff;
        text-transform: uppercase;
        font-weight: 800;
        padding: 1rem;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
        border: none;
    }
    .table-custom tbody td {
        padding: 1.5rem 1rem;
        border-bottom: 1px solid #000;
        vertical-align: top;
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }
    .table-custom tbody tr:hover {
        background: #f9f9f9;
    }

    /* INVOICE & INFO */
    .invoice-code {
        font-weight: 900;
        font-size: 1rem;
        text-transform: uppercase;
        display: block;
        margin-bottom: 0.25rem;
    }
    .order-meta {
        font-size: 0.8rem;
        color: #666;
        font-family: 'Courier New', Courier, monospace;
        display: block;
    }
    .item-list {
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: #000;
    }
    .item-list div { margin-bottom: 0.1rem; }

    /* BADGES */
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 800;
        border: 1px solid #000;
        letter-spacing: 0.05em;
        min-width: 100px;
        text-align: center;
        margin-bottom: 0.25rem;
    }
    .badge-paid { background: #000; color: #fff; }
    .badge-pending { background: #fff; color: #000; border: 1px solid #000; }
    .badge-failed { background: #000; color: #fff; text-decoration: line-through; }
    
    .badge-process { background: #fff; border: 1px dashed #000; color: #000; }
    .badge-shipped { background: #000; color: #fff; }
    .badge-done { background: #000; color: #fff; }

    /* TOTAL PRICE */
    .total-price {
        font-weight: 900;
        font-size: 1rem;
    }

    /* BUTTONS */
    .btn-action-sm {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        border: 1px solid #000;
        text-decoration: none;
        color: #000;
        font-size: 0.75rem;
        background: transparent;
        transition: all 0.2s;
        min-width: 80px;
        text-align: center;
    }
    .btn-action-sm:hover {
        background: #000;
        color: #fff;
        transform: translate(-2px, -2px);
        box-shadow: 2px 2px 0 #000;
    }
    .btn-action-sm.primary {
        background: #000;
        color: #fff;
    }
    .btn-action-sm.primary:hover {
        background: #fff;
        color: #000;
    }

    @media (max-width: 768px) {
        .table-custom thead { display: none; }
        .table-custom tbody td {
            display: block;
            text-align: right;
            padding: 0.5rem 1rem;
            border: none;
        }
        .table-custom tbody td::before {
            content: attr(data-label);
            float: left;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.8rem;
        }
        .table-custom tbody tr {
            border: 1px solid #000;
            margin-bottom: 1rem;
            display: block;
        }
        .invoice-code, .item-list, .order-meta { text-align: left; }
         /* Fix alignment for first cell in mobile */
        .table-custom tbody td:first-child { text-align: left; padding-top: 1rem; }
        .table-custom tbody td:first-child::before { display: none; }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <h2 class="page-title">Order History</h2>

    @if($orders->isEmpty())
        <div class="alert alert-dark rounded-0 border-black p-4 text-center">
            <h5 class="fw-bold text-uppercase mb-2">No Orders Found</h5>
            <p class="mb-4">You haven't placed any orders yet.</p>
            <a href="{{ route('katalog') }}" class="btn btn-brand">Shop Now</a>
        </div>
    @else
        <div class="history-card">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Order Details</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $o)
                        @php
                            $ps = $o->payment->status ?? 'pending';
                            $st = $o->status;
                            $hs = $o->handling_status ?? 'new';
                            $canPay = in_array($ps, ['pending','failed']) && $st === 'pending_payment';
                        @endphp
                        <tr>
                            <td data-label="Order Details" style="min-width: 250px;">
                                <span class="invoice-code">{{ $o->code }}</span>
                                <span class="order-meta">ID: #{{ $o->id }}</span>
                                <div class="item-list">
                                    @foreach($o->items as $it)
                                        <div>• {{ $it->product->name ?? $it->name }} ({{ $it->size }}) x{{ $it->qty }}</div>
                                    @endforeach
                                </div>
                            </td>
                            <td data-label="Date">
                                {{ $o->created_at->format('d M Y') }}<br>
                                <span class="text-muted small">{{ $o->created_at->format('H:i') }}</span>
                            </td>
                            <td data-label="Total">
                                <span class="total-price">Rp{{ number_format($o->total_amount, 0, ',', '.') }}</span>
                            </td>
                            <td data-label="Payment">
                                <span class="status-badge {{ $ps=='success'?'badge-paid':($ps=='failed'?'badge-failed':'badge-pending') }}">
                                    {{ $ps }}
                                </span>
                            </td>
                            <td data-label="Status">
                                <span class="status-badge badge-process">
                                    {{ $st == 'pending_payment' ? 'UNPAID' : ($st=='paid'?'PAID':$st) }}
                                </span>
                                @if($hs != 'new')
                                    <br>
                                    <span class="status-badge badge-shipped mt-1">{{ $hs }}</span>
                                @endif
                            </td>
                            <td data-label="Action">
                                <div class="d-flex flex-column gap-2 align-items-end align-items-md-start">
                                    <a href="{{ route('orders.history.show', $o->id) }}" class="btn-action-sm">
                                        Detail
                                    </a>
                                    @if($canPay)
                                    <a href="{{ route('checkout.pay', $o->id) }}" class="btn-action-sm primary">
                                        PAY NOW
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
