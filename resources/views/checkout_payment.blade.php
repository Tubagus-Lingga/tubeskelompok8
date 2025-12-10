<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Checkout & Pembayaran — TubesBrand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #151B54;
        }

        body {
            background: #f6f7fb;
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial;
        }

        .btn-brand {
            background: var(--brand);
            color: #fff;
            border: 0;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">TubesBrand</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-7">
                <h3 id="page-title" style="color:var(--brand)">Alamat Pengiriman</h3>

                <form id="checkout-form" class="card p-4">
                    <div class="mb-3"><label class="form-label">Nama Penerima</label><input required class="form-control"
                            id="name"></div>
                    <div class="mb-3"><label class="form-label">Alamat</label><textarea required id="address"
                            class="form-control" rows="3"></textarea></div>
                    <div class="mb-3 row">
                        <div class="col-md-6"><label class="form-label">Kota</label><input id="city"
                                class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Kode Pos</label><input id="postcode"
                                class="form-control"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Nomor Telepon</label><input id="phone"
                            class="form-control"></div>
                    <button type="submit" class="btn btn-brand">Lanjut ke Pembayaran</button>
                </form>

                <form id="payment-form" class="card p-4 hidden">
                    <div class="mb-3"><label class="form-label">Metode Pembayaran</label>
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

                    <div class="mb-3"><label class="form-label">Nama Pemegang Rekening (opsional)</label><input
                            id="accname" class="form-control"></div>
                    <button class="btn btn-brand">Konfirmasi Pembayaran</button>
                </form>
            </div>

            <div class="col-lg-5">
                <h5 style="color:var(--brand)">Ringkasan Pesanan</h5>
                <div id="summary" class="card p-3"></div>
            </div>
        </div>
    </div>

    <script>
        const HOME_URL = "{{ route('home') }}";
        const CART_KEY = 'tubes_cart_v1';

        function rupiah(n) {
            return 'Rp' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        function loadCart() {
            try {
                return JSON.parse(localStorage.getItem(CART_KEY) || '[]')
            } catch (e) {
                return []
            }
        }

        function renderSummary() {
            const cart = loadCart();
            const el = document.getElementById('summary');
            if (cart.length === 0) {
                el.innerHTML =
                    '<div class="alert alert-info">Keranjang kosong. <a href="{{ route("katalog") }}">Kembali ke Katalog</a></div>';
                return;
            }
            let subtotal = 0;
            let html = '';
            cart.forEach(it => {
                // Menggunakan it.qty (default ke 1)
                const qty = it.qty || 1;
                subtotal += it.price * qty;
                html +=
                    `<div class="d-flex justify-content-between">${it.name} <strong class="text-end">${rupiah(it.price)} x ${qty} = ${rupiah(it.price * qty)}</strong></div>`;
            });
            const shipping = subtotal > 0 ? 15000 : 0;
            html += '<hr>';
            html += `<div class="d-flex justify-content-between"><span>Subtotal</span><strong>${rupiah(subtotal)}</strong></div>`;
            html += `<div class="d-flex justify-content-between"><span>Ongkir</span><strong>${rupiah(shipping)}</strong></div>`;
            html += `<div class="d-flex justify-content-between mt-2"><span>Total</span><h5>${rupiah(subtotal + shipping)}</h5></div>`;
            el.innerHTML = html;
        }
        renderSummary();

        document.getElementById('checkout-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const shipping = {
                name: document.getElementById('name').value,
                address: document.getElementById('address').value,
                city: document.getElementById('city').value,
                postcode: document.getElementById('postcode').value,
                phone: document.getElementById('phone').value
            };
            localStorage.setItem('shipping', JSON.stringify(shipping));
            document.getElementById('checkout-form').classList.add('hidden');
            document.getElementById('payment-form').classList.remove('hidden');
            document.getElementById('page-title').innerText = 'Pembayaran';
        });

        document.getElementById('method').addEventListener('change', (e) => {
            document.getElementById('bank-fields').style.display = e.target.value === 'bank' ? 'block' : 'none';
        });

        document.getElementById('payment-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const cart = loadCart();
            if (cart.length === 0) {
                alert('Keranjang kosong. Tidak dapat memproses pesanan.');
                window.location.href = HOME_URL;
                return;
            }
            const order = {
                id: 'ORD' + Date.now(),
                cart,
                shipping: JSON.parse(localStorage.getItem('shipping') || '{}'),
                method: document.getElementById('method').value,
                bank: document.getElementById('bank').value,
                accname: document.getElementById('accname').value
            };
            localStorage.setItem('lastOrder', JSON.stringify(order));
            localStorage.removeItem(CART_KEY);
            alert('Pesanan sukses! ID: ' + order.id);
            window.location.href = HOME_URL;
        });
    </script>
</body>

</html>