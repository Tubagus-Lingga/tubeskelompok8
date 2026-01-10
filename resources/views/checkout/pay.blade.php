@extends('layouts.app')

@section('title', 'PAY QR | VIBRANT')

@section('styles')
<style>
    /* PAYMENT CARD */
    .pay-card {
        max-width: 450px;
        margin: auto;
        background: #fff;
        border: 1px solid #000;
        border-radius: 0;
        padding: 2.5rem;
        box-shadow: 10px 10px 0px rgba(0,0,0,1); /* Brutalist shadow */
        text-align: center;
    }

    .qr-box {
        padding: 1.5rem;
        background: #fff;
        border: 2px solid #000;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    /* STATUS BADGES */
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 800;
        border: 1px solid #000;
        letter-spacing: 0.05em;
        margin-bottom: 1.5rem;
    }
    .status-pending { background: #fff; color: #000; }
    .status-paid { background: #000; color: #fff; border-color: #000; }
    .status-failed { background: #000; color: #fff; text-decoration: line-through; }

    /* TYPOGRAPHY */
    .pay-title {
        font-weight: 900;
        font-size: 1.5rem;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }
    .order-code {
        font-family: 'Courier New', Courier, monospace;
        font-size: 1rem;
        color: #666;
        margin-bottom: 1rem;
        display: block;
    }

    .total-display {
        background: #000;
        color: #fff;
        padding: 1rem;
        font-weight: 800;
        font-size: 1.1rem;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
        border: 1px solid #000;
    }

    /* BUTTONS */
    .btn-action {
        width: 100%;
        padding: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        background: transparent;
        color: #000;
        border: 2px solid #000;
        transition: all 0.2s;
    }
    .btn-action:hover {
        background: #000;
        color: #fff;
        transform: translate(-4px, -4px);
        box-shadow: 4px 4px 0 #000;
    }
</style>
@endsection

@section('content')
<div class="container py-5 my-5">
    <div class="pay-card">
        @php
            $payment = $order->payment ?? null;
            $ps = $payment->status ?? 'pending';
        @endphp

        <h1 class="pay-title">Scan QR Code</h1>
        <span class="order-code">ORDER #{{ $order->code }}</span>

        <!-- STATUS -->
        <div>
            <span id="payStatusBadge" class="status-badge
                {{ $ps=='success' ? 'status-paid' : '' }}
                {{ $ps=='pending' ? 'status-pending' : '' }}
                {{ $ps=='failed' ? 'status-failed' : '' }}">
                {{ $ps }}
            </span>
        </div>

        <!-- QR -->
        <div class="qr-box">
            <img src="{{ asset('asset/qr-asset.png') }}" 
                 style="width: 200px; height: 200px; object-fit: contain; image-rendering: pixelated;" 
                 alt="QR Code">
        </div>

        <!-- TOTAL -->
        <div class="total-display">
            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
        </div>

        <!-- ACTIONS -->
        @if($payment)
            <button id="demoSuccessBtn" class="btn-action">
                [DEMO] Confirm Payment
            </button>
        @else
            <div class="alert alert-dark rounded-0">
                Payment data not found.
            </div>
        @endif

        <p class="mt-4 text-muted small text-uppercase fw-bold">
            Auto-checking status in <span id="timer">5</span>s
        </p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const paymentId = @json($payment?->id);
    const statusUrl = paymentId ? @json(route('checkout.payments.status', $payment?->id)) : null;
    const successUrl = paymentId ? @json(route('checkout.payments.success', $payment?->id)) : null;

    function renderPayBadge(status){
        const el = document.getElementById('payStatusBadge');
        if(!el) return;

        el.className = "status-badge";
        if(status === "success"){
            el.classList.add("status-paid");
        }else if(status === "failed"){
            el.classList.add("status-failed");
        }else{
            el.classList.add("status-pending");
        }
        el.textContent = status.toUpperCase();
    }

    async function pollStatus(){
        if(!statusUrl) return;
        try{
            const res = await fetch(statusUrl);
            const data = await res.json();
            renderPayBadge(data.payment_status);
            if(data.redirect_url){
                location.href = data.redirect_url;
            }
        }catch(e){
            console.error("Polling failed:", e);
        }
    }

    async function markSuccess(){
        if(!successUrl) return;
        const res = await fetch(successUrl,{
            method:"POST",
            headers:{ "X-CSRF-TOKEN": @json(csrf_token()) }
        });
        const data = await res.json();
        if(data.ok && data.redirect_url){
            location.href = data.redirect_url;
        }
    }

    if(document.getElementById('demoSuccessBtn')){
        document.getElementById('demoSuccessBtn').addEventListener('click', markSuccess);
    }

    pollStatus();
    setInterval(pollStatus, 5000);
    
    // Simple visual timer
    let count = 5;
    setInterval(() => {
        count--;
        if(count <= 0) count = 5;
        const t = document.getElementById('timer');
        if(t) t.textContent = count;
    }, 1000);
</script>
@endsection
