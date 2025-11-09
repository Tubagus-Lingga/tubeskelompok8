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
          <li class="nav-item ms-2">
            <a class="btn btn-brand" href="{{ route('cart.index') }}">
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
        <select id="sortSelect" class="form-select form-select-sm">
          <option value="default">Default</option>
          <option value="price-asc">Harga: Terendah</option>
          <option value="price-desc">Harga: Tertinggi</option>
          <option value="name-asc">Nama: A → Z</option>
        </select>
      </div>
    </div>

    <div class="row">
      <aside class="col-12 col-md-3 mb-4">
        <div class="filter-panel shadow-sm">
          <h6 class="mb-3">Filter</h6>

          <div class="mb-3">
            <label class="form-label small">Kategori</label>
            <div id="categoryList" class="d-flex flex-column gap-2"></div>
          </div>

          <div class="mb-3">
            <label class="form-label small">Harga (max)</label>
            <input id="priceMax" type="range" min="0" max="500000" step="10000" value="500000" class="form-range">
            <div class="d-flex justify-content-between small text-muted">
              <span>Rp0</span><span id="priceMaxLabel">Rp500.000</span>
            </div>
          </div>

          <div class="mb-2">
            <button id="applyFilter" class="btn btn-brand w-100">Terapkan</button>
          </div>
          <div>
            <button id="clearFilter" class="btn btn-outline-secondary w-100">Reset</button>
          </div>
        </div>
      </aside>

      <section class="col-12 col-md-9">
        <div id="productsGrid" class="row g-4"></div>

        <nav class="mt-4 d-flex justify-content-center" aria-label="Page navigation">
          <ul id="pagination" class="pagination"></ul>
        </nav>
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

    const PRODUCTS = [
      { id:1, name:"Kaos Putih Basic", category:"kaos", price:120000, image:"", desc:"Kaos cotton combed, nyaman sehari-hari."},
      { id:2, name:"Hoodie Hitam Oversize", category:"hoodie", price:250000, image:"", desc:"Fleece lembut, oversized fit."},
      { id:3, name:"Celana Chino Navy", category:"celana", price:180000, image:"", desc:"Slim-fit, bahan stretch."},
      { id:4, name:"Kaos Graphic Blue", category:"kaos", price:140000, image:"", desc:"Desain limited edition."},
      { id:5, name:"Hoodie Grey Minimal", category:"hoodie", price:230000, image:"", desc:"Warna netral, cocok dipadu-padankan."},
      { id:6, name:"Celana Jogger Hitam", category:"celana", price:150000, image:"", desc:"Santai dan fleksibel."},
      { id:7, name:"Kaos Polos Hitam", category:"kaos", price:110000, image:"", desc:"Pilihan warna solid."},
      { id:8, name:"Celana Denim Regular", category:"celana", price:200000, image:"", desc:"Denim tahan lama."}
    ];

    let state = {
      products: PRODUCTS.slice(),
      filtered: PRODUCTS.slice(),
      page: 1,
      perPage: 8,
      selectedCategory: null,
      priceMax: 500000,
      query: ''
    };

    const productsGrid = document.getElementById('productsGrid');
    const paginationEl = document.getElementById('pagination');
    const categoryList = document.getElementById('categoryList');
    const priceMaxInput = document.getElementById('priceMax');
    const priceMaxLabel = document.getElementById('priceMaxLabel');
    const applyFilterBtn = document.getElementById('applyFilter');
    const clearFilterBtn = document.getElementById('clearFilter');
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const sortSelect = document.getElementById('sortSelect');

    const categories = [...new Set(PRODUCTS.map(p => p.category))];
    function renderCategories(){
      categoryList.innerHTML = '';
      const allBtn = document.createElement('button');
      allBtn.className = 'btn btn-sm btn-outline-secondary';
      allBtn.textContent = 'Semua';
      allBtn.onclick = () => { state.selectedCategory = null; applyFilters(); highlightCategory(); };
      categoryList.appendChild(allBtn);
      categories.forEach(cat => {
        const btn = document.createElement('button');
        btn.className = 'btn btn-sm btn-outline-brand';
        btn.textContent = capitalize(cat);
        btn.style.textTransform = 'capitalize';
        btn.onclick = () => { state.selectedCategory = cat; applyFilters(); highlightCategory(); };
        btn.dataset.cat = cat;
        categoryList.appendChild(btn);
      });
      highlightCategory();
    }

    function highlightCategory(){
      const btns = categoryList.querySelectorAll('button');
      btns.forEach(b => {
        if(b.dataset.cat === state.selectedCategory) {
          b.classList.remove('btn-outline-brand'); b.classList.add('btn-brand');
        } else if(state.selectedCategory === null && b.textContent.trim().toLowerCase() === 'semua'){
          b.classList.remove('btn-outline-secondary'); b.classList.add('btn-brand');
        } else {
          if(b.dataset.cat) { b.classList.remove('btn-brand'); b.classList.add('btn-outline-brand'); }
          else { b.classList.remove('btn-brand'); b.classList.add('btn-outline-secondary'); }
        }
      });
    }

    function capitalize(s){ return s.charAt(0).toUpperCase()+s.slice(1); }
    function formatRupiah(n){ return 'Rp' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }

    function renderProducts(){
      let list = state.filtered.slice();
      const sortVal = sortSelect.value;
      if(sortVal === 'price-asc') list.sort((a,b)=>a.price-b.price);
      if(sortVal === 'price-desc') list.sort((a,b)=>b.price-a.price);
      if(sortVal === 'name-asc') list.sort((a,b)=>a.name.localeCompare(b.name));

      const total = list.length;
      const pages = Math.max(1, Math.ceil(total / state.perPage));
      if(state.page > pages) state.page = pages;

      const start = (state.page-1)*state.perPage;
      const pageItems = list.slice(start, start + state.perPage);

      productsGrid.innerHTML = pageItems.map(p => `
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card product-card h-100 shadow-sm" data-id="${p.id}" data-name="${p.name}" data-price="${p.price}">
            <img src="${p.image}" class="card-img-top" alt="${p.name}">
            <div class="card-body d-flex flex-column">
              <small class="badge badge-category mb-2">${p.category}</small>
              <h6 class="card-title mb-1">${p.name}</h6>
              <p class="small text-muted mb-2">${p.desc}</p>
              <div class="mt-auto d-flex justify-content-between align-items-center">
                <strong>${formatRupiah(p.price)}</strong>
                <div>
                 
                  <a href="/detail/${p.id}" class="btn btn-outline-brand btn-sm">Detail</a>

                  <button class="btn btn-brand btn-sm addToCartBtn">Tambah</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      `).join('');

      renderPagination(pages);

      initAddToCartButtons();
    }

    function renderPagination(totalPages){
      paginationEl.innerHTML = '';
      for(let i=1;i<=totalPages;i++){
        const li = document.createElement('li');
        li.className = 'page-item ' + (i===state.page ? 'active' : '');
        li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
        paginationEl.appendChild(li);
      }
      paginationEl.querySelectorAll('a.page-link').forEach(a=>{
        a.addEventListener('click', (e)=>{
          e.preventDefault();
          const p = parseInt(a.dataset.page);
          state.page = p; renderProducts();
        });
      });
    }

    function applyFilters(){
      let list = PRODUCTS.slice();
      if(state.selectedCategory) list = list.filter(p => p.category === state.selectedCategory);
      list = list.filter(p => p.price <= state.priceMax);
      if(state.query){
        const q = state.query.toLowerCase();
        list = list.filter(p => p.name.toLowerCase().includes(q) || p.category.toLowerCase().includes(q) || p.desc.toLowerCase().includes(q));
      }
      state.filtered = list;
      state.page = 1;
      renderProducts();
    }

    priceMaxInput.addEventListener('input', ()=>{
      priceMaxLabel.textContent = formatRupiah(parseInt(priceMaxInput.value));
    });
    applyFilterBtn.addEventListener('click', ()=>{
      state.priceMax = parseInt(priceMaxInput.value);
      applyFilters();
    });
    clearFilterBtn.addEventListener('click', ()=>{
      state.selectedCategory = null;
      state.priceMax = 500000;
      priceMaxInput.value = state.priceMax;
      priceMaxLabel.textContent = formatRupiah(state.priceMax);
      state.query = '';
      searchInput.value = '';
      sortSelect.value = 'default';
      applyFilters(); highlightCategory();
    });
    searchBtn.addEventListener('click', ()=>{ state.query = searchInput.value.trim(); applyFilters(); });
    searchInput.addEventListener('keypress', e=>{ if(e.key==='Enter'){ state.query = searchInput.value.trim(); applyFilters(); } });
    sortSelect.addEventListener('change', ()=> renderProducts());

    function getQueryParams(){
      const params = new URLSearchParams(window.location.search);
      return Object.fromEntries(params.entries());
    }
    function initFromUrl(){
      const q = getQueryParams();
      if(q.category){ state.selectedCategory = q.category; }
      if(q.q){ state.query = q.q; searchInput.value = q.q; }
      if(q.priceMax){ const v = parseInt(q.priceMax); if(!isNaN(v)){ state.priceMax = v; priceMaxInput.value = v; priceMaxLabel.textContent = formatRupiah(v); } }
    }

    const CART_KEY = 'tubes_cart_v1';
    function getCart(){ return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
    function setCart(c){ localStorage.setItem(CART_KEY, JSON.stringify(c)); updateCartCount(); }
    function updateCartCount(){
      const cart = getCart();
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
          const id = card.dataset.id;
          const name = card.dataset.name;
          const price = parseInt(card.dataset.price);
          addToCart({id, name, price});
        });
      });
    }

    (function init(){
      renderCategories();
      initFromUrl();
      applyFilters();
      updateCartCount();
    })();
  </script>
</body>
</html>
