@extends('layouts.app')

@section('title', 'VIBRANT | Streetwear')

@section('styles')
<style>
    /* HERO SECTION */
    .hero-section {
        position: relative;
        height: 90vh;
        min-height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background-color: #000;
        overflow: hidden;
        color: #fff;
    }
    .hero-bg {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0.6;
        transition: transform 0.5s ease;
    }
    .hero-section:hover .hero-bg { transform: scale(1.02); }
    
    .hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 100%);
        z-index: 2;
    }
    
    .hero-content {
        position: relative; z-index: 3;
        max-width: 900px; padding: 2rem;
        animation: slideUp 0.8s ease-out;
    }
    @keyframes slideUp { from{transform:translateY(30px);opacity:0;} to{transform:translateY(0);opacity:1;} }
    
    .hero-title {
        font-size: 6rem;
        font-weight: 900;
        line-height: 0.85;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
        color: #fff;
        letter-spacing: -0.04em;
        text-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }



    /* CATEGORY CARDS */
    .cat-card {
        display: block; position: relative;
        overflow: hidden; height: 350px;
        background: #000;
    }
    .cat-card img {
        width: 100%; height: 100%; object-fit: cover;
        transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        filter: grayscale(100%); opacity: 0.7;
    }
    .cat-card:hover img { transform: scale(1.1); opacity: 0.4; filter: grayscale(0%); }
    .cat-title {
        position: absolute; bottom: 2rem; left: 2rem;
        color: #fff; font-size: 2rem; font-weight: 900;
        text-transform: uppercase; z-index: 10;
        letter-spacing: 0.05em; transition: transform 0.3s;
    }
    .cat-card:hover .cat-title { transform: translateX(10px); }

    /* PRODUCT CARD MINIMAL */
    .product-card-min { border: none; background: transparent; transition: transform 0.3s; }
    .product-card-min:hover { transform: translateY(-5px); }
    
    .product-card-min .img-wrap {
        background: #f4f4f4; position: relative;
        width: 100%; padding-top: 100%; /* Force 1:1 Aspect Ratio via padding hack */
        overflow: hidden;
        margin-bottom: 1rem;
        display: block;
    }
    .product-card-min img {
        position: absolute; top: 0; left: 0;
        width: 100%; height: 100%; object-fit: cover;
        mix-blend-mode: multiply;
        transition: transform 0.5s ease;
    }
    .product-card-min:hover img { transform: scale(1.05); }

    .product-card-min .title {
        font-weight: 800; font-size: 1rem;
        color: #000; text-transform: uppercase; line-height: 1.1;
    }
    .product-card-min .price {
        color: #666; font-size: 0.95rem; font-weight: 500; margin-top: 0.2rem;
    }
    
    @media (max-width: 768px) {
        .hero-title { font-size: 3.5rem; }
        .hero-section { height: 70vh; }
        .cat-card { height: 250px; }
    }
    
    /* MODAL OVERRIDE */
    .modal-content { border-radius: 0; border: none; }
    .modal-header { border-bottom: 1px solid #333; }
    .modal-footer { border-top: none; }
</style>
@endsection

@section('content')

<!-- HERO -->
<section class="hero-section">
    <video class="hero-bg" autoplay muted loop playsinline>
        <source src="{{ asset('asset/div.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    
    <div class="hero-content">
        <h1 class="hero-title">
            FROM THE CULTURE<br>
            FOR THE STREETS
        </h1>
        <span class="hero-subtitle-outlined">HARD</span>
        
        <p class="hero-subtitle">
            ESSENTIALS<br>
            Limited pieces. Built from the culture. Made for everyday wear.
        </p>
        
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('katalog') }}" class="btn btn-brand btn-lg">Shop All</a>
        </div>
    </div>
</section>



<!-- CATEGORIES -->
<section class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-md-4">
            <a href="{{ route('katalog') }}?category=kaos" class="cat-card">
                <img src="{{ asset('asset/Kaos-Asset.jpg') }}" alt="Kaos">
                <span class="cat-title">T-Shirts</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('katalog') }}?category=hoodie" class="cat-card">
                <img src="{{ asset('asset/Hoodie-Asset.jpg') }}" alt="Hoodie">
                <span class="cat-title">Hoodies</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('katalog') }}?category=celana" class="cat-card">
                <img src="{{ asset('asset/Celana-Asset.jpg') }}" alt="Celana">
                <span class="cat-title">Pants</span>
            </a>
        </div>
    </div>
</section>

<!-- FEATURED DROPS -->
<section class="container py-5 my-5">
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="display-5 fw-bold mb-0">LATEST DROPS</h2>
            <p class="text-muted mb-0">Don't sleep on these.</p>
        </div>
        <a href="{{ route('katalog') }}" class="btn btn-outline-brand">View All</a>
    </div>

    <div class="row g-4">
        @if(isset($featuredProducts) && $featuredProducts->count() > 0)
            @foreach($featuredProducts as $product)
                @php
                    $imgUrl = $product->image
                        ? asset('product_images/' . $product->image)
                        : asset('images/no-image.png');
                @endphp
                <div class="col-6 col-md-3">
                    <div class="product-card-min h-100">
                        <div class="img-wrap">
                            <a href="{{ route('detail', ['slug' => $product->slug]) }}">
                                <img src="{{ $imgUrl }}" alt="{{ $product->name }}">
                            </a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <h3 class="title mb-1">
                                    <a href="{{ route('detail', ['slug' => $product->slug]) }}" class="d-block text-dark text-decoration-none">{{ $product->name }}</a>
                                </h3>
                                <div class="price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                            
                            <!-- Trigger Modal directly or go to detail -->
                            <button class="btn btn-outline-dark rounded-0 p-0 d-flex align-items-center justify-content-center quickAddBtn"
                                    style="width: 32px; height: 32px; border: 1px solid #000; flex-shrink: 0;"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}"
                                    data-image="{{ $imgUrl }}"
                                    data-variants='@json($product->variants->map(fn($v) => ["size"=>$v->size, "stock"=>$v->stock]))'>
                                <span style="font-size: 1.2rem; line-height: 0; margin-top: -2px;">+</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center py-5">
                <p class="text-muted">No drops available right now.</p>
            </div>
        @endif
    </div>
</section>

<!-- MODAL SIZE SELECTION (Keep functionality but re-style) -->
<div class="modal fade" id="sizeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-black text-white">
        <h5 class="modal-title fs-6">SELECT SIZE</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="sizeModalProductName" class="fw-bold mb-3 small text-uppercase"></p>
        <div id="sizeOptions" class="d-flex flex-wrap gap-2 justify-content-center"></div>
        <div id="sizeError" class="text-danger small mt-2 d-none text-center">
          Please select a size.
        </div>
      </div>
      <div class="modal-footer justify-content-center">
        <button class="btn btn-brand w-100" id="confirmAddToCart">ADD TO CART</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
    window.IS_LOGGED_IN = @json(auth()->check());
    window.LOGIN_URL = @json(route('login'));

    document.addEventListener('DOMContentLoaded', () => {
        const CART_KEY = 'tubes_cart_v1';
        
        function getCart(){ return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
        function setCart(c){ 
            localStorage.setItem(CART_KEY, JSON.stringify(c)); 
            // Update global counter in layout
            const total = c.reduce((s,i)=> s + (i.qty || 1), 0);
            const el = document.getElementById('global-cart-count');
            if(el) el.textContent = total;
        }

        // --- SIZE MODAL LOGIC ---
        let selectedItem = null;
        let selectedSize = null;
        let selectedStock = null;

        const sizeModalEl = document.getElementById("sizeModal");
        const sizeModal = new bootstrap.Modal(sizeModalEl);

        // Render variants
        function openSizeModal(item, variants){
            selectedItem = item;
            selectedSize = null;
            selectedStock = null;

            document.getElementById("sizeModalProductName").innerText = item.name;
            const sizeOptionsEl = document.getElementById("sizeOptions");
            sizeOptionsEl.innerHTML = "";
            document.getElementById("sizeError").classList.add("d-none");

            variants.forEach(v => {
                const b = document.createElement("button");
                b.type = "button";
                b.className = "btn btn-outline-dark btn-sm rounded-0";
                b.style.minWidth = "40px";

                const stock = parseInt(v.stock || 0, 10);
                // Check cart for current usage
                const cart = getCart();
                const cartItem = cart.find(x => x.id == item.id && x.size == v.size);
                const inCartQty = cartItem ? (cartItem.qty || 1) : 0;

                b.textContent = v.size;

                if (stock <= 0 || inCartQty >= stock) {
                    b.disabled = true;
                    b.style.textDecoration = "line-through";
                    b.style.opacity = "0.5";
                }

                b.addEventListener("click", () => {
                    selectedSize = v.size;
                    selectedStock = stock;
                    sizeOptionsEl.querySelectorAll("button").forEach(x => {
                        x.classList.remove("active", "bg-black", "text-white");
                    });
                    b.classList.add("active", "bg-black", "text-white");
                    document.getElementById("sizeError").classList.add("d-none");
                });

                sizeOptionsEl.appendChild(b);
            });
            sizeModal.show();
        }

        document.querySelectorAll('.quickAddBtn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault(); 
                const item = {
                    id: parseInt(btn.dataset.id),
                    name: btn.dataset.name,
                    price: parseInt(btn.dataset.price),
                    image: btn.dataset.image
                };
                const variants = JSON.parse(btn.dataset.variants || "[]");

                if(variants.length === 0){
                    alert("No sizes available/configured.");
                    return;
                }
                const hasStock = variants.some(v => parseInt(v.stock)>0);
                if(!hasStock){
                    alert("Out of stock.");
                    return;
                }
                openSizeModal(item, variants);
            });
        });

        document.getElementById("confirmAddToCart").addEventListener("click", () => {
            if(!selectedSize){
                document.getElementById("sizeError").classList.remove("d-none");
                return;
            }
            // Add Logic
            if(!window.IS_LOGGED_IN){
                window.location.href = window.LOGIN_URL;
                return;
            }
            
            let cart = getCart();
            const idx = cart.findIndex(x => x.id == selectedItem.id && x.size == selectedSize);
            
            if(idx >= 0){
                cart[idx].qty++;
            } else {
                cart.push({...selectedItem, size: selectedSize, qty: 1});
            }
            setCart(cart);
            sizeModal.hide();
            // Optional: Show toast
            alert("Added to cart");
        });
    });
</script>
@endsection
