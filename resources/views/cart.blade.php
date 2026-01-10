@extends('layouts.app')

@section('title', 'CART | VIBRANT')

@section('styles')
<style>
    /* CART STYLES */
    .cart-item {
        border-bottom: 1px solid #e5e5e5;
        padding: 1.5rem 0;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .cart-img-wrap {
        width: 80px;
        min-width: 80px; /* Prevent shrinking */
        height: 80px;
        background: #f4f4f4;
        flex-shrink: 0;
        overflow: hidden;
        position: relative;
    }
    .cart-img-wrap img {
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        mix-blend-mode: normal; 
        display: block;
        transition: transform 0.5s ease;
    }
    .cart-img-wrap:hover img {
        transform: scale(1.05);
    }
    
    .cart-title {
        font-weight: 800;
        font-size: 1rem;
        text-transform: uppercase;
        color: #000;
        text-decoration: none;
    }
    .cart-meta {
        font-size: 0.85rem;
        color: #666;
    }
    
    .qty-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e5e5;
        background: #fff;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.2s;
    }
    .qty-btn:hover { 
        border-color: #000; 
        background: #000;
        color: #fff;
    }
    .qty-input {
        width: 44px;
        height: 32px;
        border: 1px solid #e5e5e5;
        border-left: none;
        border-right: none;
        text-align: center;
        font-size: 0.95rem;
        font-weight: 700;
    }
    
    .summary-card {
        background: #f9f9f9;
        padding: 2rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }
    .summary-total {
        border-top: 1px solid #e5e5e5;
        padding-top: 1rem;
        margin-top: 1rem;
        font-weight: 800;
        font-size: 1.2rem;
        display: flex;
        justify-content: space-between;
    }
    
    .checkout-btn {
        width: 100%;
        padding: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-top: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <h1 class="display-5 fw-bold mb-5">YOUR CART</h1>

    <div class="row">
        <!-- LEFT: CART ITEMS -->
        <div class="col-lg-8" id="cartContainer">
            <!-- JS will render items here -->
            <div class="py-5 text-center text-muted">
                <p>Loading cart...</p>
            </div>
        </div>
        
        <!-- RIGHT: SUMMARY -->
        <div class="col-lg-4">
            <div class="summary-card sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4 text-uppercase">Summary</h5>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="summarySubtotal">Rp0</span>
                </div>
                <!-- Optional: Shipping, Tax lines usually calculated at checkout -->
                
                <div class="summary-total">
                    <span>Total</span>
                    <span id="summaryTotal">Rp0</span>
                </div>
                
                <button id="checkoutBtn" class="btn btn-brand checkout-btn">CHECKOUT</button>
                <a href="{{ route('katalog') }}" class="btn btn-link text-dark text-decoration-none w-100 mt-2 small">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const CART_KEY = 'tubes_cart_v1';
        function getCart(){ return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
        function setCart(c){ 
            localStorage.setItem(CART_KEY, JSON.stringify(c)); 
            renderCart();
            // Update global count if exists
             const total = c.reduce((s,i)=> s + (i.qty || 1), 0);
            const el = document.getElementById('global-cart-count');
            if(el) el.textContent = total;
        }
        
        const container = document.getElementById('cartContainer');
        const subtotalEl = document.getElementById('summarySubtotal');
        const totalEl = document.getElementById('summaryTotal');
        const checkoutBtn = document.getElementById('checkoutBtn');
        
        function formatMoney(n){
            return 'Rp' + n.toLocaleString('id-ID');
        }
        
        window.renderCart = function(){
            const cart = getCart();
            
            if(cart.length === 0){
                container.innerHTML = `
                    <div class="py-5 text-center">
                        <h4 class="fw-bold mb-3">YOUR CART IS EMPTY</h4>
                        <a href="{{ route('katalog') }}" class="btn btn-dark px-4">SHOP NOW</a>
                    </div>
                `;
                subtotalEl.innerText = formatMoney(0);
                totalEl.innerText = formatMoney(0);
                checkoutBtn.disabled = true;
                return;
            }
            
            checkoutBtn.disabled = false;
            let html = '';
            let total = 0;
            
            const BASE_IMG_URL = "{{ asset('product_images') }}/";
            
            cart.forEach((item, index) => {
                const itemTotal = item.price * (item.qty || 1);
                total += itemTotal;
                
                // Logic to handle both full URLs (from Katalog) and filenames (legacy/Detail)
                let displayImg = item.image || '';
                if(displayImg && !displayImg.startsWith('http') && !displayImg.startsWith('/')){
                     displayImg = BASE_IMG_URL + displayImg;
                }
                
                html += `
                    <div class="cart-item d-flex gap-3 align-items-center">
                        <div class="cart-img-wrap">
                            <img src="${displayImg}" alt="${item.name}" onerror="this.src='{{ asset('images/no-image.png') }}'">
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between mb-1">
                                <a href="#" class="cart-title">${item.name}</a>
                                <span class="fw-bold">${formatMoney(itemTotal)}</span>
                            </div>
                            <div class="cart-meta mb-2">
                                Size: ${item.size} | Price: ${formatMoney(item.price)}
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <button class="qty-btn" onclick="updateQty(${index}, -1)">-</button>
                                <input type="text" class="qty-input" value="${item.qty || 1}" readonly>
                                <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                                
                                <button class="btn btn-link text-danger text-decoration-none btn-sm ms-auto small fw-bold" onclick="removeItem(${index})">REMOVE</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
            subtotalEl.innerText = formatMoney(total);
            totalEl.innerText = formatMoney(total);
        };
        
        window.updateQty = function(index, delta){
            let cart = getCart();
            if(!cart[index]) return;
            
            let newQty = (cart[index].qty || 1) + delta;
            if(newQty < 1) return; 
            
            // Optional: Check max stock if we stored it
            // if(cart[index].maxDetails && newQty > cart[index].maxDetails) ...
            
            cart[index].qty = newQty;
            setCart(cart);
        };
        
        window.removeItem = function(index){
            if(!confirm('Remove this item?')) return;
            let cart = getCart();
            cart.splice(index, 1);
            setCart(cart);
        };
        
        checkoutBtn.addEventListener('click', () => {
             // Go to checkout page (we assume route 'checkout' or structure)
             // Or if checkout logic is not yet fully defined, we do a simple redirect
             // Based on previous summary, there might be a checkout view.
             window.location.href = "{{ route('checkout') }}"; // Standard laravel route assumed
        });
        
        renderCart();
    });
</script>
@endsection
