@extends('layouts.app')

@section('title', 'SUCCESS | VIBRANT')

@section('styles')
<style>
    .success-card {
        max-width: 520px;
        margin: 50px auto;
        background: #fff;
        border: 1px solid #000;
        padding: 3rem;
        box-shadow: 10px 10px 0px rgba(0,0,0,1); /* Brutalist shadow */
        text-align: center;
    }

    /* ANIMATED CHECK ICON */
    .icon-box {
        width: 100px; height: 100px;
        margin: 0 auto 2rem;
        border: 2px solid #000;
        display: flex; align-items: center; justify-content: center;
        background: #000;
        color: #fff;
        font-size: 3rem;
    }

    .success-title {
        font-weight: 900;
        text-transform: uppercase;
        font-size: 2rem;
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
    }

    .order-code {
        font-family: 'Courier New', Courier, monospace;
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 2rem;
        display: block;
    }

    .order-summary {
        text-align: left;
        border-top: 2px solid #000;
        border-bottom: 2px solid #000;
        padding: 1.5rem 0;
        margin-bottom: 2rem;
    }

    .summary-row {
        display: flex; justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        text-transform: uppercase;
        font-weight: 500;
    }

    .total-row {
        display: flex; justify-content: space-between;
        margin-top: 1rem;
        font-weight: 800;
        font-size: 1.2rem;
        text-transform: uppercase;
    }

    /* BUTTONS */
    .btn-action {
        display: block;
        width: 100%;
        padding: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        border: 2px solid #000;
        text-decoration: none;
        color: #000;
        margin-bottom: 1rem;
        transition: all 0.2s;
        background: transparent;
    }
    .btn-action:hover {
        background: #000;
        color: #fff;
        transform: translate(-4px, -4px);
        box-shadow: 4px 4px 0 #000;
    }
    .btn-action.secondary {
        background: #000;
        color: #fff;
    }
    .btn-action.secondary:hover {
        background: #fff;
        color: #000;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="success-card">
        
        <div class="icon-box">
            <i class="bi bi-check-lg"></i>
        </div>

        <h1 class="success-title">PAYMENT SUCCESS</h1>
        <span class="order-code">ORDER #{{ $order->code }}</span>

        <div class="order-summary">
            @foreach($order->items as $i)
                <div class="summary-row">
                    <span>{{ $i->name }} <span class="text-muted">x{{ $i->qty }}</span></span>
                    <span>Rp{{ number_format($i->subtotal,0,',','.') }}</span>
                </div>
            @endforeach
            
            <div class="total-row">
                <span>Total Paid</span>
                <span>Rp{{ number_format($order->total_amount,0,',','.') }}</span>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn-action secondary">
            Back to Home
        </a>
        <a href="{{ route('orders.history') }}" class="btn-action">
            View Order History
        </a>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
