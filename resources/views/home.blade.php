<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>TubesBrand — Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
  .btn-primary,
  .btn-brand,
  .btn-primary:visited {
  background-color: #151B54 !important;
  border-color: #151B54 !important;
  color: #ffffff !important;
  }

  .btn-outline-primary,
  .btn-outline-brand {
  color: #151B54 !important;
  border-color: #151B54 !important;
  background-color: #ffffff !important;
  }

  .btn-outline-primary:hover,
  .btn-outline-brand:hover {
  background-color: #151B54 !important;
  color: #ffffff !important;
  }

  .btn-primary:hover,
  .btn-brand:hover {
  background-color: #0f1640 !important;
  border-color: #0f1640 !important;
  }

  .navbar .btn-primary {
  background-color: #151B54 !important;
  border: none;
  color: #ffffff !important;
  }

  .navbar .btn-primary:hover {
  background-color: #0f1640 !important;
  }

  .navbar-brand { font-weight: 700; color: var(--brand); }
  .hero{
    background: linear-gradient(90deg, rgba(255,107,107,0.07), rgba(31,41,55,0.03));
    border-radius: .75rem;
    padding: 2rem;
  }

  .product-card img{
    height: 220px;
    object-fit: cover;
  }

  .badge-category{ font-size: .75rem; }
  .quick-links .card { cursor: pointer; transition: transform .12s ease; }
  .quick-links .card:hover{ transform: translateY(-6px); }
  footer{ background: #151b54;; color:#fff; padding:1.5rem 0; margin-top:3rem; }
  @media (max-width:576px){
    .product-card img{ height:180px; }
  }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">TUBESBRAND</a> <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMain">
        <form class="d-flex ms-auto my-2 my-lg-0" role="search" onsubmit="return false;">
          <input id="searchInput" class="form-control me-2" type="search" placeholder="Cari produk, mis: kaos putih" aria-label="Search">
          <button id="searchBtn" class="btn btn-outline-secondary" type="button">Cari</button>
        </form>

        <ul class="navbar-nav ms-3 mb-2 mb-lg-0 align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="{{ route('katalog') }}">Katalog</a></li> 
          <li class="nav-item"><a class="nav-link" href="#">Promo</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Bantuan</a></li>

         
         
            @auth
            <li class="nav-item dropdown ms-2">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Halo, **{{ Auth::user()->name }}**
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                    <li><a class="dropdown-item" href="#">Dashboard Saya</a></li>
                    <li><a class="dropdown-item" href="#">Riwayat Pesanan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
            @endauth

            @guest
            <li class="nav-item ms-2"><a class="btn btn-outline-primary" href="{{ route('login') }}">Masuk</a></li>
            <li class="nav-item ms-2"><a class="btn btn-primary" href="{{ route('register') }}">Daftar</a></li>
            @endguest
        
          
          <li class="nav-item ms-2">
            <a class="btn btn-primary" href="{{ route('cart.index') }}"> 
              Keranjang (<span id="cartCount">0</span>)
            </a>
          </li>
        </ul>
      </div>
    </div>
          </nav>

  <main class="container my-4">
    <section class="hero d-flex gap-4 align-items-center">
      <div class="flex-grow-1">
        <h1 class="display-6 mb-2">Koleksi Terbaru — Nyaman & Stylish</h1>
        <p class="lead text-muted mb-3">Temukan kaos, hoodie, dan celana berkualitas dari brand lokal. Gratis ongkir untuk pembelian tertentu.</p>
        <a class="btn btn-primary btn-lg me-2" href="{{ route('katalog') }}">Lihat Produk</a> 
        <a class="btn btn-outline-secondary btn-lg" href="#produk-unggulan">Produk Unggulan</a>
      </div>
      <div class="d-none d-md-block" style="max-width:360px;">
        <img src="" alt="Banner" class="img-fluid rounded">
      </div>
    </section>

    <section class="mt-4 quick-links">
      <div class="row g-3">
        <div class="col-12 col-md-4">
          <div onclick="goCategory('kaos')" class="card p-3 h-100">
            <div class="d-flex align-items-center">
              <img src="" alt="kaos" class="me-3 rounded" />
              <div>
                <h5 class="mb-0">Kaos</h5>
                <small class="text-muted">Pilihan kaos kasual</small>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4">
          <div onclick="goCategory('hoodie')" class="card p-3 h-100">
            <div class="d-flex align-items-center">
              <img src="" alt="hoodie" class="me-3 rounded" />
              <div>
                <h5 class="mb-0">Hoodie</h5>
                <small class="text-muted">Hangat & trendy</small>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4">
          <div onclick="goCategory('celana')" class="card p-3 h-100">
            <div class="d-flex align-items-center">
              <img src="" alt="celana" class="me-3 rounded" />
              <div>
                <h5 class="mb-0">Celana</h5>
                <small class="text-muted">Nyaman dipakai sehari-hari</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="produk-unggulan" class="mt-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4">Produk Unggulan</h2>
        <a href="{{ route('katalog') }}" class="text-decoration-none">Lihat semua →</a> 
      </div>

      <div id="productsGrid" class="row g-4">
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card product-card h-100" data-id="1" data-name="Kaos Putih Basic" data-price="120000">
            <img src="" class="card-img-top" alt="Kaos Putih">
            <div class="card-body d-flex flex-column">
              <small class="badge bg-secondary badge-category mb-2">Kaos</small>
              <h5 class="card-title">Kaos Putih Basic</h5>
              <p class="card-text text-muted mb-2">Bahan cotton combed, nyaman dipakai.</p>
              <div class="mt-auto d-flex justify-content-between align-items-center">
                <strong>Rp120.000</strong>
                <div>
                  <a href="{{ route('detail', ['slug' => 1]) }}" class="btn btn-outline-primary btn-sm">Detail</a> 
                  <button class="btn btn-primary btn-sm addToCartBtn">Tambah</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
          <div class="card product-card h-100" data-id="2" data-name="Hoodie Hitam Oversize" data-price="250000">
            <img src="" class="card-img-top" alt="Hoodie Hitam">
            <div class="card-body d-flex flex-column">
              <small class="badge bg-secondary badge-category mb-2">Hoodie</small>
              <h5 class="card-title">Hoodie Hitam Oversize</h5>
              <p class="card-text text-muted mb-2">Fleece lembut, cocok untuk layering.</p>
              <div class="mt-auto d-flex justify-content-between align-items-center">
                <strong>Rp250.000</strong>
                <div>
                  <a href="{{ route('detail', ['slug' => 2]) }}" class="btn btn-outline-primary btn-sm">Detail</a> 
                  <button class="btn btn-primary btn-sm addToCartBtn">Tambah</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
          <div class="card product-card h-100" data-id="3" data-name="Celana Chino Navy" data-price="180000">
            <img src="" class="card-img-top" alt="Celana Chino">
            <div class="card-body d-flex flex-column">
              <small class="badge bg-secondary badge-category mb-2">Celana</small>
              <h5 class="card-title">Celana Chino Navy</h5>
              <p class="card-text text-muted mb-2">Potongan slim-fit, bahan stretchy.</p>
              <div class="mt-auto d-flex justify-content-between align-items-center">
                <strong>Rp180.000</strong>
                <div>
                  <a href="{{ route('detail', ['slug' => 3]) }}" class="btn btn-outline-primary btn-sm">Detail</a> 
                  <button class="btn btn-primary btn-sm addToCartBtn">Tambah</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
          <div class="card product-card h-100" data-id="4" data-name="Kaos Graphic Blue" data-price="140000">
            <img src="" class="card-img-top" alt="Kaos Graphic">
            <div class="card-body d-flex flex-column">
              <small class="badge bg-secondary badge-category mb-2">Kaos</small>
              <h5 class="card-title">Kaos Graphic Blue</h5>
              <p class="card-text text-muted mb-2">Desain limited, dicetak rapi.</p>
              <div class="mt-auto d-flex justify-content-between align-items-center">
                <strong>Rp140.000</strong>
                <div>
                  <a href="{{ route('detail', ['slug' => 4]) }}" class="btn btn-outline-primary btn-sm">Detail</a> 
                  <button class="btn btn-primary btn-sm addToCartBtn">Tambah</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="mt-5 text-center">
      <h3 class="h5">Ingin melihat koleksi lengkap?</h3>
      <p class="text-muted">Kunjungi halaman katalog untuk mencari produk berdasarkan kategori, ukuran, dan harga.</p>
      <a class="btn btn-lg btn-outline-primary" href="{{ route('katalog') }}">Buka Katalog</a> 
    </section>

  </main>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5>TUBESBRAND</h5>
          <p style="color: #fff;">Platform e-commerce fashion lokal. Hak cipta &copy; <span id="year"></span></p>
        </div>
        <div class="col-md-6 text-md-end">
          <small style="color: #fff;">Kontak: hello@tubesbrand.example | Telp: 0853-XXXX-XXXX</small>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('year').textContent = new Date().getFullYear();

    function goCategory(cat){
      window.location.href = '{{ route('katalog') }}?category=' + encodeURIComponent(cat);
    }

    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    searchBtn.addEventListener('click', doSearch);
    searchInput.addEventListener('keypress', function(e){
      if(e.key === 'Enter') doSearch();
    });

    function doSearch(){
      const q = searchInput.value.trim().toLowerCase();
      const cards = document.querySelectorAll('#productsGrid .product-card');
      if(!q){
        cards.forEach(c => c.closest('.col-6').style.display = '');
        return;
      }
      cards.forEach(card => {
        const name = (card.dataset.name || '').toLowerCase();
        const category = (card.dataset.category || '').toLowerCase();
        const matches = name.includes(q) || category.includes(q);
        card.closest('.col-6').style.display = matches ? '' : 'none';
      });
    }

    const CART_KEY = 'tubes_cart_v1';
    function getCart() { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
    function setCart(cart) { localStorage.setItem(CART_KEY, JSON.stringify(cart)); updateCartCount(); }
    function updateCartCount() {
      const cart = getCart();
      const count = cart.reduce((s, i) => s + (i.qty || 1), 0);
      const el = document.getElementById('cartCount');
      if(el) el.textContent = count;
    }

    function addToCart(item) {
      let cart = getCart();
      const idx = cart.findIndex(x => x.id == item.id);
      if(idx >= 0) {
        cart[idx].qty = (cart[idx].qty || 1) + 1;
      } else {
        cart.push({...item, qty:1});
      }
      setCart(cart);
      alert(item.name + " berhasil ditambahkan ke keranjang!");
    }

    document.querySelectorAll('.addToCartBtn').forEach(btn => {
      btn.addEventListener('click', () => {
        const card = btn.closest('.product-card');
        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = parseInt(card.dataset.price);
        addToCart({ id, name, price });
      });
    });

    updateCartCount();
  </script>
</body>
</html>
