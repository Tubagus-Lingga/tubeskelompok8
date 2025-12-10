<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --brand: #151B54; }
        body { background: #f6f7fb; font-family: Inter, Arial, sans-serif; }
        .btn-brand { background: var(--brand); color: white; border: none; }
    </style>
</head>

<body>
<nav class="navbar bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}" style="color: var(--brand);">TUBESBRAND</a>
        <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary">Keranjang ...</a>

        </a>
    </div>
</nav>

<div class="container my-4">
    <h3>Keranjang Belanja</h3>
    <div id="cartItems" class="mt-3"></div>

    <div id="summary" class="card p-3 mt-3">
        <div class="d-flex justify-content-between">
            <strong>Subtotal</strong><span id="subtotal">Rp0</span>
        </div>
        <div class="d-flex justify-content-between">
            <strong>Ongkir</strong><span id="shipping">Rp0</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between fw-bold fs-5">
            <div>Total</div><div id="total">Rp0</div>
        </div>

        <button class="btn btn-brand w-100 mt-3" id="checkoutBtn">Checkout</button>
        <div id="info" class="small text-muted mt-2"></div>
    </div>
</div>

<script>
    const CART_KEY = 'tubes_cart_v1';
    const CHECKOUT_URL = "{{ route('checkout.index') }}";
    const SHIPPING_FEE = 20000;

    const rupiah = (num) => 'Rp' + Number(num || 0).toLocaleString('id-ID');

    function getCart() { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
    function setCart(cart) { localStorage.setItem(CART_KEY, JSON.stringify(cart)); renderCart(); }

    function updateCartCount() {
        const cart = getCart();
        const count = cart.reduce((a, b) => a + (b.qty || 1), 0);
        document.getElementById('cartCount').textContent = count;
    }

    function renderCart() {
        const cart = getCart();
        const container = document.getElementById('cartItems');
        container.innerHTML = '';

        if (cart.length === 0) {
            container.innerHTML = '<div class="alert alert-info">Keranjang masih kosong.</div>';
        } else {
            cart.forEach((item, idx) => {
                const div = document.createElement('div');
                div.className = 'd-flex justify-content-between align-items-center border-bottom py-2';
                div.innerHTML = `
                    <div>
                        <strong>${item.name}</strong><br>
                        <small>Harga: ${rupiah(item.price)} x ${item.qty}</small>
                    </div>
                    <div>
                        <span class="fw-bold">${rupiah(item.price * item.qty)}</span>
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeItem(${idx})">Hapus</button>
                    </div>
                `;
                container.appendChild(div);
            });
        }

        updateSummary();
        updateCartCount();
    }

    function updateSummary() {
        const cart = getCart();
        let subtotal = cart.reduce((sum, i) => sum + (Number(i.price) * Number(i.qty || 1)), 0);
        let shipping = subtotal > 0 ? SHIPPING_FEE : 0;
        let total = subtotal + shipping;

        document.getElementById('subtotal').textContent = rupiah(subtotal);
        document.getElementById('shipping').textContent = rupiah(shipping);
        document.getElementById('total').textContent = rupiah(total);

        const checkoutBtn = document.getElementById('checkoutBtn');
        checkoutBtn.disabled = cart.length === 0;
        checkoutBtn.textContent = cart.length === 0 ? 'Keranjang Kosong' : 'Checkout';
    }

    function removeItem(index) {
        let cart = getCart();
        cart.splice(index, 1);
        setCart(cart);
    }

    document.getElementById('checkoutBtn').addEventListener('click', () => {
        if (getCart().length === 0) return;
        window.location.href = CHECKOUT_URL; // ✅ masuk halaman form alamat/pembayaran
    });

    renderCart();
</script>
</body>
</html>
