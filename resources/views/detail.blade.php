<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TubesBrand — Detail Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand: #151B54;
            --white: #fff;
            --muted: #6b7280;
        }

        body {
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            background: #f6f7fb;
            color: #111827;
        }

        .navbar-brand {
            color: var(--brand);
            font-weight: 700;
        }

        .btn-brand {
            background: var(--brand);
            color: var(--white);
            border: none;
        }

        .btn-outline-brand {
            border-color: var(--brand);
            color: var(--brand);
        }

        .product-image {
            width: 100%;
            height: 420px;
            object-fit: cover;
            border-radius: .5rem;
        }

        .badge-cat {
            background: rgba(21, 27, 84, 0.08);
            color: var(--brand);
            font-weight: 600;
            padding: .35rem .6rem;
            border-radius: .5rem;
        }

        footer {
            background: #151B54;
            color: #fff;
            padding: 1.5rem 0;
            margin-top: 2.5rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">TUBESBRAND</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('katalog') }}">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-brand" href="{{ route('cart.index') }}">Keranjang
                            (<span id="cartCount">0</span>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <div id="productArea" class="row g-4">
            <div class="col-12 text-center py-5 text-muted">Memuat produk...</div>
        </div>

        <section id="relatedSection" class="mt-5">
            <h5>Produk Terkait</h5>
            <div id="relatedGrid" class="row g-3 mt-2"></div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 style="color:#fff">TUBESBRAND</h5>
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

        // === DATA PRODUK ===
        // CATATAN: Dalam implementasi Laravel sesungguhnya, data ini harus diambil dari Controller, bukan di-hardcode di JS.
        const PRODUCTS = [{
                id: 1,
                name: "Kaos Putih Basic",
                category: "kaos",
                price: 120000,
                image: "",
                desc: "Kaos cotton combed, nyaman sehari-hari."
            },
            {
                id: 2,
                name: "Hoodie Hitam Oversize",
                category: "hoodie",
                price: 250000,
                image: "",
                desc: "Fleece lembut, oversized fit."
            },
            {
                id: 3,
                name: "Celana Chino Navy",
                category: "celana",
                price: 180000,
                image: "",
                desc: "Slim-fit, bahan stretch."
            },
            {
                id: 4,
                name: "Kaos Graphic Blue",
                category: "kaos",
                price: 140000,
                image: "",
                desc: "Desain limited edition."
            },
            {
                id: 5,
                name: "Hoodie Grey Minimal",
                category: "hoodie",
                price: 230000,
                image: "",
                desc: "Warna netral, cocok dipadu-padankan."
            },
            {
                id: 6,
                name: "Celana Jogger Hitam",
                category: "celana",
                price: 150000,
                image: "",
                desc: "Santai dan fleksibel."
            },
            {
                id: 7,
                name: "Kaos Polos Hitam",
                category: "kaos",
                price: 110000,
                image: "",
                desc: "Pilihan warna solid."
            },
            {
                id: 8,
                name: "Celana Denim Regular",
                category: "celana",
                price: 200000,
                image: "",
                desc: "Denim tahan lama."
            }
        ];

        // === UTIL ===
        function getQueryParams() {
            // Mengambil ID dari URL path /detail/{id} jika menggunakan route, atau dari query ?id=
            const pathParts = window.location.pathname.split('/');
            const idFromPath = pathParts.length > 2 && pathParts[pathParts.length - 2] === 'detail' ? pathParts[pathParts
                .length - 1] : null;

            const params = new URLSearchParams(window.location.search);

            return {
                id: idFromPath || params.get('id')
            };
        }

        function formatRupiah(n) {
            return 'Rp' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // === SISTEM KERANJANG ===
        const CART_KEY = 'tubes_cart_v1';

        function getCart() {
            return JSON.parse(localStorage.getItem(CART_KEY) || '[]');
        }

        function setCart(c) {
            localStorage.setItem(CART_KEY, JSON.stringify(c));
            updateCartCount();
        }

        function updateCartCount() {
            const cart = getCart();
            const total = cart.reduce((sum, i) => sum + (i.qty || 1), 0);
            const el = document.querySelector('#cartCount');
            if (el) el.textContent = total;
        }

        function addToCart(item) {
            const cart = getCart();
            const idx = cart.findIndex(p => p.id == item.id);
            if (idx >= 0) cart[idx].qty += 1;
            else cart.push({
                ...item,
                qty: 1
            });
            setCart(cart);
            alert(item.name + " berhasil ditambahkan ke keranjang!");
        }

        // === RENDER DETAIL PRODUK ===
        function renderProductDetail(product) {
            const html = `
        <div class="col-12 col-md-6">
          <img src="${product.image}" alt="${product.name}" class="product-image shadow-sm">
        </div>
        <div class="col-12 col-md-6">
          <div class="p-3 bg-white rounded shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h3 class="mb-0">${product.name}</h3>
              <span class="badge-cat">${product.category}</span>
            </div>
            <p class="text-muted small">${product.desc}</p>
            <h4 class="mt-3">${formatRupiah(product.price)}</h4>

            <div class="mt-4">
              <label class="form-label small">Ukuran</label>
              <select id="sizeSelect" class="form-select mb-3 w-50">
                <option value="S">S</option><option value="M">M</option><option value="L">L</option><option value="XL">XL</option>
              </select>

              <div class="d-flex gap-2">
                <button id="addCartBtn" class="btn btn-brand btn-lg">Tambah ke Keranjang</button>
                                <a href="{{ route('katalog') }}" class="btn btn-outline-secondary btn-lg">Kembali ke Katalog</a>
              </div>

              <div id="msg" class="mt-3" style="display:none"></div>
            </div>
          </div>
        </div>
      `;
            document.getElementById('productArea').innerHTML = html;

            // Event Tambah ke Keranjang
            document.getElementById('addCartBtn').addEventListener('click', () => {
                const size = document.getElementById('sizeSelect').value;
                addToCart({
                    id: product.id,
                    name: product.name + " (" + size + ")",
                    price: product.price
                });
            });
        }

        // === PRODUK TERKAIT ===
        function renderRelated(product) {
            const related = PRODUCTS.filter(p => p.category === product.category && p.id !== product.id).slice(0, 4);
            const grid = document.getElementById('relatedGrid');
            if (related.length === 0) {
                grid.innerHTML = '<div class="col-12 small text-muted">Tidak ada produk terkait.</div>';
                return;
            }
            grid.innerHTML = related.map(p => `
        <div class="col-6 col-md-3">
          <div class="card h-100 shadow-sm">
            <img src="${p.image}" class="card-img-top" style="height:140px; object-fit:cover" alt="${p.name}">
            <div class="card-body p-2">
              <h6 class="small mb-1">${p.name}</h6>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">${formatRupiah(p.price)}</small>
                                <a href="{{ route('detail', ['id' => 'RELATED_ID']) }}".replace('RELATED_ID', ${p.id})" class="btn btn-sm btn-outline-brand">Lihat</a>
              </div>
            </div>
          </div>
        </div>
      `).join('');
        }

        // === INISIALISASI ===
        (function init() {
            const q = getQueryParams();
            let id = parseInt(q.id);

            if (isNaN(id)) {
                document.getElementById('productArea').innerHTML = `
          <div class="col-12 text-center py-5">
            <h5 class="mb-3">Produk tidak ditemukan</h5>
            <p class="text-muted mb-3">Parameter id tidak valid atau tidak disertakan.</p>
                        <a class="btn btn-outline-secondary" href="{{ route('katalog') }}">Kembali ke Katalog</a>
          </div>
        `;
                return;
            }

            const product = PRODUCTS.find(p => p.id === id);
            if (!product) {
                document.getElementById('productArea').innerHTML = `
            <div class="col-12 text-center py-5">
            <h5 class="mb-3">Produk tidak ditemukan</h5>
            <p class="text-muted mb-3">Produk dengan id ${id} tidak tersedia.</p>
            <a class="btn btn-outline-secondary" href="{{ route('katalog') }}">Kembali ke Katalog</a>
            </div>
            `;
                return;
            }

            renderProductDetail(product);
            renderRelated(product);
            updateCartCount();
        })();
    </script>
</body>

</html>