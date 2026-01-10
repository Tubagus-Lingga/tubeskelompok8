@extends('layouts.app')

@section('title', 'CHECKOUT | VIBRANT')

@section('styles')
<style>
    /* CHECKOUT STYLES */
    .checkout-title {
        font-weight: 800;
        font-size: 1.5rem;
        text-transform: uppercase;
        margin-bottom: 2rem;
    }
    
    .form-label {
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .form-control {
        border-radius: 0;
        border: 1px solid #e5e5e5;
        padding: 0.75rem;
        font-size: 0.95rem;
    }
    .form-control:focus {
        border-color: #000;
        box-shadow: none;
    }
    
    /* CART ITEM IN CHECKOUT */
    .checkout-item {
        border-bottom: 1px solid #f4f4f4;
        padding: 1rem 0;
    }
    .checkout-item:last-child { border-bottom: none; }
    
    .checkout-img {
        width: 60px;
        height: 60px;
        background: #f4f4f4;
        object-fit: cover;
        mix-blend-mode: multiply;
    }
    .checkout-item-title {
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        line-height: 1.2;
    }
    .checkout-item-meta {
        font-size: 0.8rem;
        color: #666;
    }
    
    /* SUMMARY & PAY */
    .summary-box {
        background: #f9f9f9;
        padding: 2rem;
    }
    .summary-row {
        display: flex; justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .summary-total {
        border-top: 1px solid #e5e5e5;
        padding-top: 1rem;
        margin-top: 1rem;
        font-weight: 800;
        font-size: 1.1rem;
        display: flex; justify-content: space-between;
    }
    
    .payment-option {
        border: 1px solid #e5e5e5;
        padding: 1rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        background: #fff;
        transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .payment-option:hover { 
        border-color: #000;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .payment-option.active {
        border-color: #000;
        background: #000;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .payment-option.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f4f4f4;
        transform: none;
        box-shadow: none;
    }
    .payment-option.active .text-muted { color: #ccc !important; }

    .btn-checkout {
        width: 100%;
        padding: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        background: #000;
        color: #fff;
        border: none;
        margin-top: 1.5rem;
    }
    .btn-checkout:hover { background: #333; }
    
    .btn-qty {
        border: 1px solid #ddd;
        background: #fff;
        width: 24px; height: 24px;
        display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: 0.8rem;
    }
    .btn-remove {
        color: #999;
        font-size: 0.8rem;
        text-decoration: none;
        font-weight: 600;
        text-transform: uppercase;
    }
    .btn-remove:hover { color: #d00; }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- LEFT: FORM & ITEMS -->
        <div class="col-lg-7 mb-5">
            <h2 class="checkout-title">Shipping Details</h2>
            
            <div class="mb-5">
                <div class="mb-3">
                    <label class="form-label">Full Address</label>
                    <input id="alamat" class="form-control" placeholder="Street name, number...">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">City</label>
                        <input id="city" class="form-control" placeholder="City name...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">District</label>
                        <input id="district" class="form-control" placeholder="District/Kecamatan...">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Postal Code</label>
                        <input id="postal_code" class="form-control" placeholder="12345...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input id="telp" class="form-control" placeholder="08...">
                    </div>
                </div>
            </div>
            
            <h2 class="checkout-title">Order Items</h2>
            <div id="cartList" class="mb-5">
                <!-- Items rendered via JS -->
                <div class="text-muted small">Loading items...</div>
            </div>
        </div>
        
        <!-- RIGHT: SUMMARY & PAYMENT -->
        <div class="col-lg-5">
            <div class="summary-box sticky-top" style="top: 100px;">
                <h5 class="fw-bold text-uppercase mb-4">Order Summary</h5>
                
                <div class="summary-row">
                    <span>Items Total</span>
                    <span id="itemPriceText">Rp0</span>
                </div>
                <div class="summary-row d-none" id="deliveryRow">
                    <span>Delivery Fee</span>
                    <span id="deliveryText">Rp0</span>
                </div>
                 <div class="summary-row text-success">
                    <span>Discount</span>
                    <span id="saleText">- Rp0</span>
                </div>
                
                <div class="summary-total d-none" id="totalRow">
                    <span>TOTAL</span>
                    <span id="totalText">Rp0</span>
                </div>
                
                <hr class="my-4 border-secondary">
                
                <div id="paymentSection" class="d-none">
                    <h5 class="fw-bold text-uppercase mb-3">Payment Method</h5>
                    
                    <div id="pay-qris" class="payment-option" data-method="qris">
                        <div class="fw-bold">QRIS</div>
                        <div class="small text-muted">Scan via GoPay, OVO, Dana, etc.</div>
                    </div>
                    
                    <div id="pay-bank" class="payment-option disabled" data-method="bank">
                        <div class="fw-bold">Bank Transfer</div>
                        <div class="small text-muted">Not available yet</div>
                    </div>
                    
                    <div id="pay-cod" class="payment-option disabled" data-method="cod">
                        <div class="fw-bold">Cash On Delivery</div>
                        <div class="small text-muted">Not available yet</div>
                    </div>
                    
                    <!-- ALERTS -->
                    <div id="info-qris" class="alert alert-dark mt-3 d-none small">
                        QR Code will appear after you proceed.
                    </div>
                    <div id="info-bank" class="alert alert-warning mt-3 d-none small">
                        Bank Transfer is currently unavailable.
                    </div>
                    
                    <button id="processBtn" class="btn-checkout">PLACE ORDER</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.IS_LOGGED_IN = @json(auth()->check());
    window.LOGIN_URL   = @json(route('login'));

    const CART_KEY = 'tubes_cart_v1';
    const DELIVERY_FEE = 15000;

    function getCart(){ return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
    function setCart(c){ localStorage.setItem(CART_KEY, JSON.stringify(c)); renderCart(); }

    function rupiah(n){ return 'Rp' + Number(n||0).toLocaleString('id-ID'); }

    function removeItem(id,size){
        if(!confirm("Remove item?")) return;
        let cart = getCart().filter(i => !(i.id==id && i.size==size));
        setCart(cart);
    }

    function updateQty(id,size,delta){
        let cart = getCart();
        let idx = cart.findIndex(i => i.id==id && i.size==size);
        if(idx >= 0){
            let newQty = (cart[idx].qty || 1) + delta;
            if(newQty>=1) cart[idx].qty = newQty;
        }
        setCart(cart);
    }

    function renderCart(){
        const cart = getCart();
        const list = document.getElementById('cartList');

        // Update global badge
         const totalItems = cart.reduce((s,i)=> s + (i.qty || 1), 0);
        const el = document.getElementById('global-cart-count');
        if(el) el.textContent = totalItems;

        if(cart.length===0){
            list.innerHTML = `<div class="alert alert-secondary rounded-0">Your cart is empty. <a href="{{ route('katalog') }}" class="fw-bold text-dark">Shop now</a></div>`;
            updateSummary(0);
            return;
        }

        let subtotal = 0;
        list.innerHTML = cart.map(item=>{
            const qty = item.qty||1;
            const price = item.price;
            subtotal += qty*price;

            return `
            <div class="checkout-item d-flex gap-3 align-items-start">
                <img src="${item.image}" class="checkout-img" onerror="this.src='{{ asset('images/no-image.png') }}'">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between mb-1">
                        <div class="checkout-item-title">${item.name}</div>
                        <div class="fw-bold small">${rupiah(price * qty)}</div>
                    </div>
                    <div class="checkout-item-meta mb-2">Size: ${item.size}</div>
                    
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                             <div class="btn-qty" onclick="updateQty(${item.id}, '${item.size}', -1)">-</div>
                             <span class="small fw-bold px-1">${qty}</span>
                             <div class="btn-qty" onclick="updateQty(${item.id}, '${item.size}', 1)">+</div>
                        </div>
                        <a href="javascript:void(0)" class="btn-remove" onclick="removeItem(${item.id}, '${item.size}')">REMOVE</a>
                    </div>
                </div>
            </div>`;
        }).join('');

        updateSummary(subtotal);
    }

    function updateSummary(subtotal){
        document.getElementById('itemPriceText').textContent = rupiah(subtotal);
        document.getElementById('deliveryText').textContent = rupiah(DELIVERY_FEE);
        document.getElementById('totalText').textContent = rupiah(subtotal + DELIVERY_FEE);
    }

    // Payment Logic
    let selectedPayment = null;
    document.querySelectorAll('.payment-option').forEach(opt=>{
        opt.addEventListener('click', ()=>{
            const method = opt.dataset.method;
            if(opt.classList.contains('disabled')){
                if(method === 'bank') {
                     document.getElementById('info-bank').classList.remove('d-none');
                     setTimeout(()=>document.getElementById('info-bank').classList.add('d-none'), 3000);
                }
                return;
            }

            document.querySelectorAll('.payment-option').forEach(o=>o.classList.remove('active'));
            opt.classList.add('active');
            selectedPayment = method;

            document.getElementById('info-qris').classList.add('d-none');
            if(method === 'qris') document.getElementById('info-qris').classList.remove('d-none');
        });
    });

    // Checkout Process
    document.getElementById('processBtn').onclick = async()=>{
        if(!selectedPayment){ alert("Please select a payment method."); return; }
        if(!window.IS_LOGGED_IN){ 
            alert("Please login first."); 
            window.location.href = window.LOGIN_URL; 
            return; 
        }

        const cart = getCart();
        if(cart.length===0){ alert("Cart is empty."); return; }

        const alamat = document.getElementById('alamat').value.trim();
        const city = document.getElementById('city').value.trim();
        const district = document.getElementById('district').value.trim();
        const postal_code = document.getElementById('postal_code').value.trim();
        const telp   = document.getElementById('telp').value.trim();

        if(!alamat || !city || !district || !postal_code || !telp){ 
            alert("Please fill in all address fields."); 
            return; 
        }

        // Using fetch to post
        try {
            const res = await fetch("{{ route('checkout.process') }}",{
                method:"POST",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN":"{{ csrf_token() }}"
                },
                body:JSON.stringify({
                    cart,
                    shipping:{alamat, city, district, postal_code, telp},
                    method:selectedPayment
                })
            });
            const data = await res.json();

            if(data.ok){
                localStorage.removeItem(CART_KEY); // Clear cart
                window.location.href = data.redirect_url;
            } else {
                alert("Checkout failed: " + (data.message || "Unknown error"));
            }
        } catch(e){
            console.error(e);
            alert("Something went wrong.");
        }
    };

    // Progressive form: show delivery/payment when address and phone are filled
    function checkFormFields() {
        const alamat = document.getElementById('alamat').value.trim();
        const city = document.getElementById('city').value.trim();
        const district = document.getElementById('district').value.trim();
        const postal_code = document.getElementById('postal_code').value.trim();
        const telp = document.getElementById('telp').value.trim();
        const deliveryRow = document.getElementById('deliveryRow');
        const totalRow = document.getElementById('totalRow');
        const paymentSection = document.getElementById('paymentSection');
        
        if (alamat && city && district && postal_code && telp) {
            deliveryRow.classList.remove('d-none');
            totalRow.classList.remove('d-none');
            paymentSection.classList.remove('d-none');
            updateSummary(getCart().reduce((s,i)=>(s + (i.qty||1) * i.price), 0));
        } else {
            deliveryRow.classList.add('d-none');
            totalRow.classList.add('d-none');
            paymentSection.classList.add('d-none');
        }
    }
    
    document.getElementById('alamat').addEventListener('input', checkFormFields);
    document.getElementById('city').addEventListener('input', checkFormFields);
    document.getElementById('district').addEventListener('input', checkFormFields);
    document.getElementById('postal_code').addEventListener('input', checkFormFields);
    document.getElementById('telp').addEventListener('input', checkFormFields);

    renderCart();
</script>
@endsection
