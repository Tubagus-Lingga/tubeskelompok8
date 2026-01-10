<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Checkout & Pembayaran — VIBRANT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root { --brand:#151B54; }
        body{
            background:#f6f7fb;
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial;
        }
        .btn-brand{
            background:var(--brand);
            color:#fff;
            border:0;
            font-weight:600;
        }
        .btn-brand:hover{
            background:#0f1640;
            color:#fff;
        }
        .hidden{ display:none; }
        .summary-card img{
            width:60px;height:60px;object-fit:cover;
            border-radius:8px;border:1px solid #eee;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}" style="color:var(--brand)">VIBRANT</a>
    </div>
</nav>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-lg-7">
            <h3 id="page-title" style="color:var(--brand)">Alamat Pengiriman</h3>

            <form id="checkout-form" class="card p-4 shadow-sm">
                <div class="mb-3">
                    <label class="form-label">Nama Penerima</label>
                    <input required class="form-control" id="name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea required id="address" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <label class="form-label">Kota</label>
                        <input id="city" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kode Pos</label>
                        <input id="postcode" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input required id="phone" class="form-control">
                </div>

                <button type="submit" class="btn btn-brand">
                    Lanjut ke Pembayaran
                </button>
            </form>

            <form id="payment-form" class="card p-4 shadow-sm hidden mt-3">
                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select id="method" class="form-select">
                        <option value="bank">Transfer Bank</option>
                        <option value="va">Virtual Account</option>
                        <option value="cod">Bayar di Tempat (COD)</option>
                    </select>
                </div>

                <div id="bank-fields" class="mb-3">
                    <label class="form-label">Pilih Bank</label>
                    <select id="bank" class="form-select">
                        <option>Bank Mandiri</option>
                        <option>BNI</option>
                        <option>BCA</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Pemegang Rekening (opsional)</label>
                    <input id="accname" class="form-control">
                </div>

                <button class="btn btn-brand w-100" type="submit">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>

        <div class="col-lg-5">
            <h5 style="color:var(--brand)">Ringkasan Pesanan</h5>
            <div id="summary" class="card p-3 shadow-sm summary-card"></div>
        </div>
    </div>
</div>

<script>
    window.IS_LOGGED_IN = @json(auth()->check());
    window.LOGIN_URL = @json(route('login'));

    const HOME_URL = "{{ route('home') }}";
    const CART_KEY = "tubes_cart_v1";
    const SHIPPING_KEY = "shipping";
    const SHIPPING_FEE = 15000;

    function rupiah(n){
        return "Rp" + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function loadCart(){
        try { return JSON.parse(localStorage.getItem(CART_KEY) || "[]"); }
        catch(e){ return []; }
    }

    function saveShipping(data){
        localStorage.setItem(SHIPPING_KEY, JSON.stringify(data));
    }

    function getShipping(){
        try { return JSON.parse(localStorage.getItem(SHIPPING_KEY) || "{}"); }
        catch(e){ return {}; }
    }

    function renderSummary(){
        const cart = loadCart();
        const el = document.getElementById("summary");

        if(cart.length === 0){
            el.innerHTML = `
                <div class="alert alert-info m-0">
                    Keranjang kosong. 
                    <a href="{{ route('katalog') }}">Kembali ke katalog</a>
                </div>
            `;
            return;
        }

        let subtotal = 0;
        let html = "";

        cart.forEach(it => {
            const qty  = it.qty || 1;
            const size = it.size || "-";
            const img  = it.image || "{{ asset('images/no-image.png') }}";

            subtotal += it.price * qty;

            html += `
                <div class="d-flex gap-2 align-items-center mb-2">
                    <img src="${img}" alt="${it.name}">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${it.name}</div>
                        <small class="text-muted">Ukuran: ${size}</small><br>
                        <small>${rupiah(it.price)} × ${qty}</small>
                    </div>
                    <div class="fw-bold text-end">
                        ${rupiah(it.price * qty)}
                    </div>
                </div>
            `;
        });

        const shipping = subtotal > 0 ? SHIPPING_FEE : 0;

        html += `<hr>`;
        html += `
            <div class="d-flex justify-content-between">
                <span>Subtotal</span>
                <strong>${rupiah(subtotal)}</strong>
            </div>
            <div class="d-flex justify-content-between">
                <span>Ongkir</span>
                <strong>${rupiah(shipping)}</strong>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <span>Total</span>
                <h5 class="m-0">${rupiah(subtotal + shipping)}</h5>
            </div>
        `;

        el.innerHTML = html;
    }
    renderSummary();

    // ====== STEP 1: SHIPPING ======
    document.getElementById("checkout-form").addEventListener("submit", (e) => {
        e.preventDefault();

        const shipping = {
            alamat: document.getElementById("address").value,
            telp: document.getElementById("phone").value,
            name: document.getElementById("name").value,
            city: document.getElementById("city").value,
            postcode: document.getElementById("postcode").value
        };

        saveShipping(shipping);

        document.getElementById("checkout-form").classList.add("hidden");
        document.getElementById("payment-form").classList.remove("hidden");
        document.getElementById("page-title").innerText = "Pembayaran";
    });

    document.getElementById("method").addEventListener("change", (e) => {
        document.getElementById("bank-fields").style.display =
            e.target.value === "bank" ? "block" : "none";
    });

    // ====== STEP 2: PAYMENT -> POST ke Laravel ======
    document.getElementById("payment-form").addEventListener("submit", async (e) => {
        e.preventDefault();

        if(!window.IS_LOGGED_IN){
            alert("Harus login terlebih dahulu!");
            location.href = window.LOGIN_URL;
            return;
        }

        const cart = loadCart();
        if(cart.length === 0){
            alert("Keranjang kosong.");
            location.href = "{{ route('katalog') }}";
            return;
        }

        const shipping = getShipping();

        try {
            const res = await fetch("{{ route('checkout.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ cart, shipping })
            });

            const data = await res.json();

            if(data.ok){
                localStorage.removeItem(CART_KEY);
                localStorage.removeItem(SHIPPING_KEY);
                location.href = data.redirect_url;
            } else {
                alert("Checkout gagal: " + (data.message || "Terjadi kesalahan"));
            }

        } catch(err){
            console.error(err);
            alert("Gagal terhubung ke server.");
        }
    });
</script>
</body>
</html>
