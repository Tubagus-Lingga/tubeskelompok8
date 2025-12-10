<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pembayaran Berhasil</title>
</head>
<body style="font-family:system-ui; background:#f6f7fb; margin:0; padding:24px;">
@php
  $amount = $order->payment?->amount ?? $order->total_amount ?? 0;
  $dt = $order->paid_at;
@endphp

<div style="max-width:430px; margin:0 auto;">
  <div style="text-align:center; padding:10px 12px 10px;">
    <div style="width:72px; height:72px; border-radius:999px; margin:0 auto 12px; background:#2ecc71; display:flex; align-items:center; justify-content:center;">
      <span style="color:white; font-size:36px; font-weight:900;">✓</span>
    </div>

    <div style="font-size:22px; font-weight:800; color:#111827;">Pembayaran Berhasil!</div>
    <div style="margin-top:6px; font-size:12px; color:#6b7280;">
      {{ $dt ? $dt->format('d M Y • H:i') : '-' }} WIB
    </div>
  </div>

  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:18px; padding:16px; box-shadow:0 16px 40px rgba(17,24,39,.08);">
    <div style="text-align:center; color:#6b7280; font-size:12px;">Nominal Transaksi</div>
    <div style="text-align:center; font-weight:900; font-size:28px; color:#111827; margin-top:2px;">
      Rp {{ number_format((int)$amount, 0, ',', '.') }}
    </div>

    <div style="margin:12px 0 10px; border-top:1px dashed #e5e7eb;"></div>

    <div style="color:#6b7280; font-size:12px;">Kode Order</div>
    <div style="font-weight:800; color:#111827;">{{ $order->code }}</div>

    <div style="margin-top:12px; color:#6b7280; font-size:12px;">Items</div>

    @if(($order->items ?? collect())->count() === 0)
      <div style="margin-top:8px; color:#111827;">(Tidak ada item)</div>
    @else
      <ul style="margin:8px 0 0; padding-left:18px; color:#111827;">
        @foreach($order->items as $it)
          <li style="margin:6px 0;">
            {{ $it->name }} x{{ $it->qty }} — Rp {{ number_format((int)$it->subtotal,0,',','.') }}
          </li>
        @endforeach
      </ul>
    @endif
  </div>

  <div style="margin-top:14px; text-align:center;">
    <a href="{{ route('checkout.index') }}" style="text-decoration:none; color:#2563eb; font-weight:800;">
      Kembali ke Keranjang
    </a>
  </div>
</div>

<script>

  localStorage.removeItem('tubes_cart_v1');
  localStorage.removeItem('shipping');


</script>
</body>
</html>
