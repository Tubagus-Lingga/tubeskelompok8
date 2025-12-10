<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Bayar QR</title>
</head>
<body style="font-family:system-ui; margin:0; padding:24px; background:#0b1220; color:#e5e7eb;">
@php
  $amount = $order->payment?->amount ?? $order->total_amount;
@endphp

<div style="max-width:480px; margin:0 auto;">
  <h2 style="margin:0 0 12px;">Pembayaran</h2>

  <div style="background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12); border-radius:16px; padding:16px;">
    <div style="color:#9ca3af; font-size:12px;">Nama Barang</div>
    <div style="font-weight:800; margin-bottom:10px;">
      {{ $order->items->pluck('name')->join(', ') }}
    </div>

    <div style="color:#9ca3af; font-size:12px;">Total (Termasuk Ongkir)</div>
    <div style="font-weight:900; font-size:22px; margin-bottom:14px;">
      Rp {{ number_format($amount,0,',','.') }}
    </div>

    <div style="display:flex; justify-content:center; margin:10px 0 12px;">
      <img src="{{ $qrSrc }}" alt="QR"
           style="width:220px; height:220px; object-fit:contain; background:#fff; border-radius:12px; padding:10px;">
    </div>

    <div style="text-align:center; color:#9ca3af; font-size:13px; margin-top:6px;">
      Pengecekan Pembayaran...
    </div>

    <div id="hint" style="text-align:center; color:#9ca3af; font-size:12px; margin-top:8px;"></div>

    <button id="btnSim"
      style="margin-top:14px; width:100%; padding:12px; border-radius:12px; border:0; background:#2563eb; color:#fff; font-weight:800; cursor:pointer;">
      Simulasikan Pembayaran Berhasil
    </button>
  </div>
</div>

<script>
  const statusUrl = "{{ route('checkout.payments.status', $order->payment->id) }}";
  const successUrl = "{{ route('checkout.payments.success', $order->payment->id) }}";
  const csrf = document.querySelector('meta[name="csrf-token"]').content;
  const hint = document.getElementById('hint');
  const btnSim = document.getElementById('btnSim');

  async function checkStatus() {
    try {
      const res = await fetch(statusUrl, { headers: { "Accept": "application/json" } });
      const json = await res.json();

      if (json.payment_status === 'success' && json.redirect_url) {
        window.location.href = json.redirect_url;
        return;
      }

      hint.textContent = `Status: ${json.payment_status}`;
    } catch (e) {
      hint.textContent = 'Gagal cek status, coba lagi...';
    }
  }

  setInterval(checkStatus, 2000);
  checkStatus();

  btnSim?.addEventListener('click', async () => {
    btnSim.disabled = true;
    btnSim.textContent = 'Memproses...';
    await fetch(successUrl, { method: "POST", headers: { "X-CSRF-TOKEN": csrf, "Accept":"application/json" } });
    await checkStatus();
    btnSim.disabled = false;
    btnSim.textContent = 'Simulasikan Pembayaran Berhasil';
  });
</script>
</body>
</html>
