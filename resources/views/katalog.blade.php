<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TubesBrand — Katalog</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root{
            --brand: #151B54;
            --white: #ffffff;
            --black: #000000;
            --muted: #6b7280;
            --card-bg: #fbfdff;
        }
        body{ font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial; background:#f6f7fb; color:var(--black); }
        .navbar-brand { color: var(--brand); font-weight:700; }
        .page-title { color: var(--brand); }
        .product-card img{ height: 220px; object-fit:cover; border-bottom: 1px solid rgba(0,0,0,0.04); }
        .btn-brand { background: var(--brand); color: var(--white); border: none; }
        .btn-outline-brand { border-color: var(--brand); color: var(--brand); }
        .badge-category{ background: rgba(21,27,84,0.08); color: var(--brand); font-weight:600; }
        .filter-panel { background: var(--card-bg); border-radius: .5rem; padding: 1rem; }
        .pagination .page-link { color: var(--brand); }
        footer{ background:#151B54; color:#fff; padding:1.5rem 0; margin-top:2.5rem; }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">TUBESBRAND</a> 
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain2">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain2">
                <form class="d-flex ms-auto my-2 my-lg-0" role="search" onsubmit="return false;">
                    <input id="searchInput" class="form-control me-2" type="search" placeholder="Cari produk..." aria-label="Search">
                    <button id="searchBtn" class="btn btn-outline-brand" type="button">Cari</button>
                </form>
                <ul class="navbar-nav ms-3 mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('katalog') }}">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    @auth
                        <li class="nav-item ms-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Keluar ({{ Auth::user()->name }})</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item ms-2"><a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Masuk</a></li>
                    @endauth
                    <li class="nav-item ms-2">
                        <a class="btn btn-brand" href="{{ route('checkout.index') }}">
                            Keranjang (<span id="cartCount">0</span>)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="page-title h4 mb-0">Katalog Produk</h1>
                <small class="text-muted">Temukan koleksi terbaik kami</small>
            </div>

            <div class="d-flex gap-2 align-items-center">
                <label class="mb-0 me-2 text-muted">Sortir:</label>
                <select id="sortSelect" class="form-select form-select-sm" disabled>
                    <option value="default">Default</option>
                    {{-- Opsi ini memerlukan logika di Controller Laravel untuk berfungsi --}}
                </select>
            </div>
        </div>

        <div class="row">
            <aside class="col-12 col-md-3 mb-4">
                <div class="filter-panel shadow-sm">
                    <h6 class="mb-3">Filter</h6>
                    <p class="small text-muted">Fitur Filter Dinonaktifkan (perlu logika server)</p>
                    
                    <div class="mb-3">
                        <label class="form-label small">Kategori</label>
                        <div id="categoryList" class="d-flex flex-column gap-2"></div>
                    </div>
                    
                    <div class="mb-2">
                        <button id="applyFilter" class="btn btn-brand w-100" disabled>Terapkan</button>
                    </div>
                    <div>
                        <button id="clearFilter" class="btn btn-outline-secondary w-100" disabled>Reset</button>
                    </div>
                </div>
            </aside>
            <section class="col-12 col-md-9">
                <div class="row g-4">
                    
                    {{-- *** START: BLADE LOOP UNTUK MENAMPILKAN DATA DARI LARAVEL *** --}}
                    
                    @if($products->isEmpty())
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                Belum ada produk yang tersedia.
                            </div>
                        </div>
                    @else
                        @foreach($products as $product)
                        <div class="col-6 col-md-4 col-lg-4">
                            <div class="card product-card h-100 shadow-sm" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                
                                {{-- Pastikan kolom 'image' ada di database, gunakan asset() atau url storage --}}
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                
                                <div class="card-body d-flex flex-column">
                                    {{-- Pastikan kolom 'category' ada --}}
                                    <small class="badge badge-category mb-2">{{ $product->category }}</small>
                                    
                                    <h6 class="card-title mb-1">{{ $product->name }}</h6>
                                    
                                    {{-- Tampilkan deskripsi singkat, menggunakan Str::limit jika Anda mengimpor facade Str --}}
                                    <p class="small text-muted mb-2">{{ Str::limit($product->desc ?? $product->description, 50) }}</p>
                                    
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        {{-- Format harga Rupiah --}}
                                        <strong>Rp{{ number_format($product->price, 0, ',', '.') }}</strong>
                                        <div>
                                            {{-- Link Detail (Menggunakan Slug atau ID) --}}
                                            <a href="{{ route('detail', ['slug' => $product->slug ?? $product->id]) }}" class="btn btn-outline-brand btn-sm">Detail</a> 
                                            
                                            <button class="btn btn-brand btn-sm addToCartBtn">Tambah</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                    
                    {{-- *** END: BLADE LOOP *** --}}
                </div>

                {{-- Pagination Laravel (gunakan ini jika Anda menggunakan Product::paginate()) --}}
                {{-- <nav class="mt-4 d-flex justify-content-center" aria-label="Page navigation">
                    {{ $products->links() }}
                </nav> --}}
            </section>
            </div>
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>TUBESBRAND</h5>
                    <p style="color: #fff;">E-commerce fashion lokal. Hak cipta &copy; <span id="year"></span></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small style="color: #fff;">Kontak: hello@tubesbrand.example</small>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
        
        // HAPUS SEMUA LOGIKA JAVASCRIPT LOKAL TERKAIT DATA/FILTER/SORT/PAGINATION YANG LAMA.
        // Hanya pertahankan logika untuk Cart (Keranjang) karena itu masih menggunakan localStorage.
        
        const CART_KEY = 'tubes_cart_v1';
        
        function getCart(){ return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
        function setCart(c){ localStorage.setItem(CART_KEY, JSON.stringify(c)); updateCartCount(); }
        
        function updateCartCount(){
            const cart = getCart();
            // Asumsi cart menyimpan item dengan properti 'qty'
            const total = cart.reduce((sum,i)=> sum + (i.qty||1), 0);
            const el = document.querySelector('#cartCount');
            if(el) el.textContent = total;
        }

        function addToCart(item){
            const cart = getCart();
            const idx = cart.findIndex(p=>p.id==item.id);
            if(idx>=0) cart[idx].qty += 1; else cart.push({...item, qty:1});
            setCart(cart);
            alert(item.name + " berhasil ditambahkan ke keranjang!");
        }

        function initAddToCartButtons(){
            document.querySelectorAll('.addToCartBtn').forEach(btn=>{
                btn.addEventListener('click', ()=>{
                    const card = btn.closest('.product-card');
                    const id = parseInt(card.dataset.id); // Pastikan ID diubah ke integer
                    const name = card.dataset.name;
                    const price = parseInt(card.dataset.price);
                    addToCart({id, name, price});
                });
            });
        }
        
        // Inisialisasi saat halaman dimuat
        (function init(){
            initAddToCartButtons(); // Tambah ke Keranjang masih berfungsi
            updateCartCount();
        })();
    </script>
</body>
</html>