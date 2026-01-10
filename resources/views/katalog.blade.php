@extends('layouts.app')

@section('title', 'SHOP | VIBRANT')

@section('styles')
<style>
    /* CATALOG SPECIFIC STYLES */
    .filter-group {
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .filter-title {
        font-weight: 800;
        font-size: 0.9rem;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }
    .filter-link {
        display: block;
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        transition: color 0.2s;
        text-transform: capitalize;
    }
    .filter-link:hover, .filter-link.active {
        color: #000;
        font-weight: 600;
    }

    /* PRODUCT GRID */
    .product-grid-item {
        margin-bottom: 2rem;
        padding-bottom: 1rem; /* Ensure spacing */
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .product-img-wrap {
        position: relative;
        background: #f4f4f4;
        width: 100%;
        padding-top: 100%; /* Force 1:1 Aspect Ratio via padding hack */
        overflow: hidden;
        margin-bottom: 1rem;
        display: block;
        border-radius: 0;
    }
    .product-img-wrap img {
        position: absolute;
        top: 0; left: 0;
        width: 100%; 
        height: 100%;
        object-fit: cover;
        mix-blend-mode: multiply;
        transition: transform 0.5s ease;
        display: block;
    }
    .product-img-wrap:hover img {
        transform: scale(1.05);
    }
    
    .product-meta {
        text-align: left;
    }
    .product-title {
        font-weight: 800;
        font-size: 1rem;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
        color: #000;
        display: block;
    }
    .product-price {
        font-weight: 500;
        color: #666;
        font-size: 0.9rem;
    }
    
    /* PAGINATION */
    .page-link {
        border: none;
        color: #000;
        font-weight: 600;
        background: transparent;
    }
    .page-item.active .page-link {
        background: #000;
        color: #fff;
        border-radius: 0;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    
    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h1 class="display-5 fw-bold mb-0">SHOP</h1>
            <p class="text-muted mb-0">
                @if(request('q'))
                    Search results for "{{ request('q') }}"
                @else
                    All Collections
                @endif
            </p>
        </div>
        
        <!-- SORTING -->
        <form id="sortForm" method="GET" action="{{ route('katalog') }}" class="d-flex align-items-center">
            @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            
            <label class="small fw-bold me-2 text-uppercase">Sort By:</label>
            <select name="sort" class="form-select form-select-sm rounded-0 border-0 bg-light" style="width: auto; font-weight:600;" onchange="this.form.submit()">
                <option value="default" {{ $sort==='default' ? 'selected' : '' }}>Relevance</option>
                <option value="price_asc" {{ $sort==='price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ $sort==='price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </form>
    </div>

    <div class="row">
        <!-- SIDEBAR -->
        <aside class="col-md-3 mb-5">
            <div class="sticky-top" style="top: 100px;">
                <!-- SEARCH WIDGET -->
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase mb-2">Search</h6>
                    <form action="{{ route('katalog') }}" method="GET" class="position-relative">
                        <!-- Preserve current category if searching -->
                        @if(request('category')) 
                            <input type="hidden" name="category" value="{{ request('category') }}"> 
                        @endif
                         @if(request('sort')) 
                            <input type="hidden" name="sort" value="{{ request('sort') }}"> 
                        @endif
                        
                        <input type="text" name="q" 
                               class="form-control rounded-0 form-control-sm border-dark ps-2" 
                               placeholder="Search products..." 
                               value="{{ request('q') }}">
                        <button type="submit" class="btn btn-link position-absolute top-0 end-0 text-dark p-1 text-decoration-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- CATEGORY WIDGET -->
                <div class="filter-group border-0">
                    <h6 class="fw-bold text-uppercase mb-2">Categories</h6>
                    <nav class="nav flex-column gap-1">
                        <a href="{{ route('katalog', ['q'=>request('q'), 'sort'=>request('sort')]) }}" 
                           class="filter-link {{ !request('category') ? 'active fw-bold text-black' : '' }}">
                           All Collections
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('katalog', [
                                    'category' => $cat, 
                                    'q' => request('q'), 
                                    'sort' => request('sort')
                               ]) }}" 
                               class="filter-link {{ request('category') === $cat ? 'active fw-bold text-black' : '' }}">
                               {{ ucfirst($cat) }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </aside>

        <!-- PRODUCT GRID -->
        <div class="col-md-9">
            @if($products->isEmpty())
                <div class="py-5 text-center">
                    <h4 class="fw-bold">No products found.</h4>
                    <a href="{{ route('katalog') }}" class="btn btn-outline-brand mt-3">Clear Filters</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($products as $product)
                         @php
                            $imgUrl = $product->image
                                ? asset('product_images/' . $product->image)
                                : asset('images/no-image.png');
                        @endphp
                        
                        <div class="col-6 col-md-4">
                            <div class="product-grid-item h-100">
                                <div class="product-img-wrap">
                                    <a href="{{ route('detail', ['slug' => $product->slug]) }}">
                                        <img src="{{ $imgUrl }}" alt="{{ $product->name }}">
                                    </a>
                                    <!-- Optional Badge -->
                                    <!-- <span class="badge bg-black text-white position-absolute top-0 start-0 m-2 rounded-0">NEW</span> -->
                                </div>
                                <div class="product-meta mt-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('detail', ['slug' => $product->slug]) }}" class="product-title d-block text-dark text-decoration-none" style="font-weight: 900; font-size: 1rem; line-height: 1.1;">
                                                {{ $product->name }}
                                            </a>
                                            <span class="product-price d-block text-muted mt-1" style="font-size: 0.9rem; font-weight: 500;">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                        </div>
                                        
                                        <button class="btn btn-outline-dark rounded-0 p-0 d-flex align-items-center justify-content-center quickAddBtn"
                                            style="width: 32px; height: 32px; border: 1px solid #000; flex-shrink: 0;"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->price }}"
                                            data-image="{{ $imgUrl }}"
                                            data-variants='@json($product->variants->map(fn($v) => ["size"=>$v->size, "stock"=>$v->stock]))'
                                            title="Add to Cart">
                                            <span style="font-size: 1.2rem; line-height: 0; margin-top: -2px;">+</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }} 
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Re-use the Size Modal Logic from Layout if we put it there, OR duplicate script section -->
<!-- Ideally, move Size Modal to Layout to avoid duplication. For now, I'll inject the modal here too, or minimal script override -->
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
          Select size.
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
            const total = c.reduce((s,i)=> s + (i.qty || 1), 0);
            const el = document.getElementById('global-cart-count');
            if(el) el.textContent = total;
        }

        let selectedItem=null, selectedSize=null;

        const sizeModal = new bootstrap.Modal(document.getElementById("sizeModal"));

        function openSizeModal(item, variants){
            selectedItem = item; selectedSize = null;
            document.getElementById("sizeModalProductName").innerText = item.name;
            const sizeOptionsEl = document.getElementById("sizeOptions");
            sizeOptionsEl.innerHTML = "";
            document.getElementById("sizeError").classList.add("d-none");

            variants.forEach(v => {
                const b = document.createElement("button");
                b.type = "button";
                b.className = "btn btn-outline-dark btn-sm rounded-0";
                b.style.minWidth="40px";
                
                const stock = parseInt(v.stock||0);
                 b.textContent = v.size;

                if(stock<=0) { b.disabled=true; b.style.opacity="0.5"; b.style.textDecoration="line-through"; }

                b.addEventListener("click", ()=>{
                    selectedSize = v.size;
                    sizeOptionsEl.querySelectorAll("button").forEach(x=>x.classList.remove("active","bg-black","text-white"));
                    b.classList.add("active","bg-black","text-white");
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
                if(!variants.some(v=>parseInt(v.stock)>0)) { alert("Out of stock"); return; }
                openSizeModal(item, variants);
            });
        });

        document.getElementById("confirmAddToCart").addEventListener("click", () => {
            if(!selectedSize) { document.getElementById("sizeError").classList.remove("d-none"); return; }
            if(!window.IS_LOGGED_IN){ window.location.href = window.LOGIN_URL; return; }
            
            let cart = getCart();
            const idx = cart.findIndex(x => x.id == selectedItem.id && x.size == selectedSize);
            if(idx >= 0) cart[idx].qty++; else cart.push({...selectedItem, size: selectedSize, qty: 1});
            
            setCart(cart);
            sizeModal.hide();
            alert("Added to cart");
        });
    });
</script>
@endsection
