<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout — TubesBrand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --brand: #151B54; }
        body { background:#f6f7fb; font-family: Inter, system-ui, Segoe UI, Roboto, Arial; }
        .btn-brand { background: var(--brand); color: #fff; border: 0; }
        .hidden { display: none !important; }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}" style="color:var(--brand)">TubesBrand</a>
        <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary">Keranjang</a>
    </div>
</nav>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-lg-7">
            <h3 id="page-title" class="mb-3" style="color:var(--brand)">Checkout</h3>

            {{-- STEP 1: ALAMAT --}}
            <div id="stepAddress" class="card p-4 shadow-sm">
                <h5 class="mb-3">1) Alamat Pengiriman</h5>

                <div class="mb-3">
                    <label class="form-label">Nama Penerima</label>
                    <input required class="form-control" id="name" placeholder="Nama lengkap">
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea required id="address" class="form-control" rows="3" placeholder="Nama jalan, nomor rumah, dll"></textarea>
                </div>

                <div class="mb-3 row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kota</label>
                        <input id="city" class="form-control" placeholder="Contoh: Bandung">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kode Pos</label>
                        <input id="postcode" class="form-control" placeholder="Contoh: 40123">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input id="phone" class="form-control" placeholder="08xxxxxxxxxx">
                </div>

                <button id="toPayment" type="button" class="btn btn-brand w-100">Lanjut ke Metode Pembayaran</button>
                <div class="text-muted small mt-2">Ongkir flat: <strong>Rp20.000</strong>.</div>
            </div>

            {{-- STEP 2: METODE PEMBAYARAN + SUBMIT --}}
            <div id="stepPayment" class="card p-4 shadow-sm hidden mt-3">
                <h5 class="mb-3">2) Metode Pembayaran</h5>

                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select id="method" class="form-select">
                    <option value="qris" selected>QRIS</option>
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
                    <input id="accname" class="form-control" placeholder="Nama sesuai rekening">
                </div>

                <hr class="my-3">

                <h6 class="mb-2">Ringkasan Total</h6>
                <div class="d-flex justify-content-between"><span>Subtotal</span><strong id="sumSubtotal">Rp0</strong></div>
                <div class="d-flex justify-content-between"><span>Ongkir</span><strong id="sumShipping">Rp0</strong></div>
                <div class="d-flex justify-content-between mt-2"><span class="fw-bold">Total</span><strong class="fs-5" id="sumTotal">Rp0</strong></div>

                <button id="payBtn" type="button" class="btn btn-brand w-100 mt-3">Bayar & Tampilkan QR</button>
                <div id="payStatus" class="small text-muted mt-3"></div>

                <button id="backToAddress" type="button" class="btn btn-link mt-2">← Kembali isi alamat</button>
            </div>
        </div>

        <div class="col-lg-5">
            <h5 class="mb-3" style="color:var(--brand)">Keranjang</h5>
            <div id="cartPreview" class="card p-3 shadow-sm"></div>
        </div>
    </div>
</div>

<script>
    const CART_KEY = 'tubes_cart_v1';
    const SHIPPING_FEE = 20000;
    const CHECKOUT_PROCESS_URL = "{{ route('checkout.process') }}";
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    function rupiah(n) {
        n = Number(n || 0);
        return 'Rp' + n.toLocaleString('id-ID');
    }

    function loadCart() {
        try { return JSON.parse(localStorage.getItem(CART_KEY) || '[]') }
        catch (e) { return [] }
    }

    function calcSubtotal(cart) {
        return cart.reduce((sum, it) => sum + (Number(it.price || 0) * Number(it.qty || 1)), 0);
    }

    function renderCartPreview() {
        const cart = loadCart();
        const el = document.getElementById('cartPreview');

        if (!cart.length) {
            el.innerHTML = `<div class="alert alert-info mb-0">Keranjang kosong. Silakan tambah produk dulu.</div>`;
            return;
        }

        let html = '';
        cart.forEach(it => {
            const qty = Number(it.qty || 1);
            const price = Number(it.price || 0);
            html += `
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-semibold">${it.name ?? '-'}</div>
                        <div class="text-muted small">${rupiah(price)} x ${qty}</div>
                    </div>
                    <div class="fw-bold">${rupiah(price * qty)}</div>
                </div>
            `;
        });

        el.innerHTML = html;
    }

    function renderTotals() {
        const cart = loadCart();
        const subtotal = calcSubtotal(cart);
        const shipping = subtotal > 0 ? SHIPPING_FEE : 0;
        const total = subtotal + shipping;

        document.getElementById('sumSubtotal').textContent = rupiah(subtotal);
        document.getElementById('sumShipping').textContent = rupiah(shipping);
        document.getElementById('sumTotal').textContent = rupiah(total);
    }

    // INIT
    document.addEventListener('DOMContentLoaded', () => {
        const cart = loadCart();
        renderCartPreview();
        renderTotals();

        if (!cart.length) {
            document.getElementById('toPayment').disabled = true;
            document.getElementById('toPayment').textContent = 'Keranjang Kosong';
        }
    });

    // alamat -> pembayaran
    document.getElementById('toPayment').addEventListener('click', () => {
        const cart = loadCart();
        if (!cart.length) return;

        const name = document.getElementById('name').value.trim();
        const address = document.getElementById('address').value.trim();

        if (!name || !address) {
            alert('Nama & alamat wajib diisi.');
            return;
        }

        localStorage.setItem('shipping', JSON.stringify({
            name,
            address,
            city: document.getElementById('city').value.trim(),
            postcode: document.getElementById('postcode').value.trim(),
            phone: document.getElementById('phone').value.trim(),
        }));

        document.getElementById('stepAddress').classList.add('hidden');
        document.getElementById('stepPayment').classList.remove('hidden');
        renderTotals();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    document.getElementById('backToAddress').addEventListener('click', () => {
        document.getElementById('stepPayment').classList.add('hidden');
        document.getElementById('stepAddress').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // method change -> bank fields
const methodEl = document.getElementById('method');
const bankFields = document.getElementById('bank-fields');

function enforceQris() {
    const val = methodEl.value;

    // cuma QRIS yang aktif
    if (val !== 'qris') {
        alert('Metode Pembayaran belum tersedia. Silakan gunakan QRIS.');
        methodEl.value = 'qris';
    }

    // karena QRIS, field bank disembunyikan
    bankFields.style.display = 'none';
}

// pas halaman kebuka: paksa QRIS
enforceQris();

// pas dropdown berubah: validasi
methodEl.addEventListener('change', () => {
    enforceQris();
});

   

    // submit -> backend create order -> redirect QR
    document.getElementById('payBtn').addEventListener('click', async () => {
        const cart = loadCart();
        if (!cart.length) {
            alert('Keranjang kosong.');
            window.location.href = "{{ route('checkout.index') }}";
            return;
        }

        const shipping = JSON.parse(localStorage.getItem('shipping') || '{}');

        const payload = {
            cart,
            shipping,
            payment: {
                mmethod: 'qris',
                bank: null,
                accname: document.getElementById('accname').value
            }
        };

        const payBtn = document.getElementById('payBtn');
        const payStatus = document.getElementById('payStatus');
        payBtn.disabled = true;
        payBtn.textContent = 'Memproses...';
        payStatus.textContent = 'Membuat order & menyiapkan QR...';

        try {
            const res = await fetch(CHECKOUT_PROCESS_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const json = await res.json().catch(() => ({}));

            if (!res.ok || !json.redirect_url) {
                payStatus.textContent = json?.message ? `Gagal: ${json.message}` : 'Gagal memproses checkout.';
                payBtn.disabled = false;
                payBtn.textContent = 'Bayar & Tampilkan QR';
                return;
            }

            // ✅ clear cart localStorage agar balik keranjang kosong setelah sukses nanti
            // (kalau kamu mau clear setelah sukses saja, pindahkan ke halaman sukses)
            localStorage.removeItem(CART_KEY);

            payStatus.textContent = 'Berhasil. Mengarahkan ke halaman QR...';
            window.location.href = json.redirect_url;

        } catch (err) {
            payStatus.textContent = 'Error: ' + (err?.message || err);
            payBtn.disabled = false;
            payBtn.textContent = 'Bayar & Tampilkan QR';
        }
    });

    document.getElementById('checkout-form').classList.remove('hidden');
    document.getElementById('payment-form').classList.add('hidden');

</script>
</body>
</html>
