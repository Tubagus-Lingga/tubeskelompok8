<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Checkout</title>
</head>

<body style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu; background:#0b1220; color:#e5e7eb; margin:0; padding:24px;">
<div style="max-width:760px;margin:0 auto;">
  <h1 style="margin:0 0 10px;">Checkout</h1>
  <div style="color:#9ca3af;margin-bottom:18px;">
    Pilih produk dari database → checkout → bayar sukses (simulasi) → tampil nominal sesuai total.
  </div>

  <div style="background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12); border-radius:16px; padding:16px;">
    <div style="display:flex; gap:12px; flex-wrap:wrap;">
      <label style="flex:1; min-width:280px;">
        <div style="font-size:12px; color:#9ca3af; margin-bottom:6px;">Produk</div>
        <select id="product_id"
                style="width:100%; padding:10px; border-radius:12px; border:1px solid rgba(255,255,255,.15); background:rgba(0,0,0,.2); color:#e5e7eb;">
          @forelse($products as $p)
            <option value="{{ $p->id }}" data-price="{{ (int)$p->price }}">
              {{ $p->name }} — Rp {{ number_format($p->price, 0, ',', '.') }}
            </option>
          @empty
            <option value="">(Belum ada produk di database)</option>
          @endforelse
        </select>
      </label>

      <label style="width:140px;">
        <div style="font-size:12px; color:#9ca3af; margin-bottom:6px;">Qty</div>
        <input id="qty" type="number" min="1" value="1"
               style="width:100%; padding:10px; border-radius:12px; border:1px solid rgba(255,255,255,.15); background:rgba(0,0,0,.2); color:#e5e7eb;" />
      </label>
    </div>

    <div style="margin-top:12px; color:#9ca3af; font-size:13px;">
      Estimasi total: <b id="estimate" style="color:#e5e7eb;">Rp -</b>
    </div>

    <button id="btnPay"
            style="margin-top:14px; width:100%; padding:12px 14px; border-radius:12px; border:0; background:#2563eb; color:white; font-weight:800; cursor:pointer;">
      Checkout & Bayar (Simulasi)
    </button>

    <div id="status" style="margin-top:12px; color:#9ca3af; font-size:13px;"></div>

    <div style="margin-top:12px; font-size:12px; color:#9ca3af;">
      Note: Total final dihitung di backend dari database (anti manipulasi).
    </div>
  </div>
</div>

<script>
  const csrf = document.querySelector('meta[name="csrf-token"]').content;
  const elProduct = document.getElementById('product_id');
  const elQty = document.getElementById('qty');
  const elEstimate = document.getElementById('estimate');
  const elStatus = document.getElementById('status');
  const btnPay = document.getElementById('btnPay');

  function formatRupiah(n) {
    try { return new Intl.NumberFormat('id-ID').format(n); }
    catch { return String(n); }
  }

  function updateEstimate() {
    const opt = elProduct.options[elProduct.selectedIndex];
    const price = parseInt(opt?.dataset?.price || '0', 10);
    const qty = parseInt(elQty.value || '1', 10);
    const total = price * qty;

    elEstimate.textContent = price ? `Rp ${formatRupiah(total)}` : 'Rp -';
  }

  elProduct?.addEventListener('change', updateEstimate);
  elQty?.addEventListener('input', updateEstimate);
  updateEstimate();

  btnPay.addEventListener('click', async () => {
    const productId = parseInt(elProduct.value || '0', 10);
    const qty = parseInt(elQty.value || '1', 10);

    if (!productId) {
      elStatus.textContent = 'Produk belum tersedia di database.';
      return;
    }

    btnPay.disabled = true;
    btnPay.textContent = 'Memproses...';
    elStatus.textContent = 'Membuat order...';

    try {
      // 1) create checkout
      const res = await fetch("{{ route('checkout.process') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrf,
          "Accept": "application/json"
        },
        body: JSON.stringify({
          items: [{ product_id: productId, qty }]
        })
      });

      const json = await res.json();

      if (!res.ok) {
        elStatus.textContent = json?.message ? `Gagal: ${json.message}` : 'Gagal checkout.';
        return;
      }

      elStatus.textContent = 'Pembayaran sukses (simulasi)...';

      // 2) trigger payment success
      const payRes = await fetch(json.payment_success_url, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrf,
          "Accept": "text/html"
        }
      });

      // endpoint success melakukan redirect, tapi fetch tidak otomatis pindah page
      // maka kita arahkan manual ke success page:
      window.location.href = json.success_page_url;

    } catch (e) {
      elStatus.textContent = 'Error: ' + (e?.message || e);
    } finally {
      btnPay.disabled = false;
      btnPay.textContent = 'Checkout & Bayar (Simulasi)';
    }
  });
</script>
</body>
</html>
