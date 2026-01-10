<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Pesanan | VIBRANT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root { --brand:#151B54; }
        .badge-hs-new{background:#6c757d;}
        .badge-hs-processing{background:#ffc107;color:#000;}
        .badge-hs-shipped{background:#0dcaf0;color:#000;}
        .badge-hs-done{background:#198754;}
        .badge-order-paid{background:#198754;}
        .badge-order-pending{background:#ffc107;color:#000;}
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}" style="color:var(--brand)">VIBRANT</a>
        <div class="ms-auto">
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('katalog') }}">Shop</a>
        </div>
    </div>
</nav>

<main class="container my-4">
    <h4 class="mb-3 fw-bold" style="color:var(--brand)">Riwayat Pesanan</h4>

    @if($orders->isEmpty())
        <div class="alert alert-info">Belum ada pesanan.</div>
    @else
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status Bayar</th>
                            <th>Status Pesanan</th>
                            <th width="110">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $o)
                        @php
                            $paymentStatus = $o->payment->status ?? 'pending';
                            $handling = $o->handling_status ?? 'new';
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $o->code }}</td>
                            <td>{{ $o->created_at->format('d M Y H:i') }}</td>
                            <td>Rp{{ number_format($o->total_amount,0,',','.') }}</td>

                            {{-- Status Bayar --}}
                            <td>
                                <span class="badge {{ $paymentStatus==='success' ? 'badge-order-paid' : 'badge-order-pending' }}">
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            </td>

                            {{-- Status Penanganan --}}
                            <td>
                                <span class="badge
                                    {{ $handling==='new' ? 'badge-hs-new' : '' }}
                                    {{ $handling==='processing' ? 'badge-hs-processing' : '' }}
                                    {{ $handling==='shipped' ? 'badge-hs-shipped' : '' }}
                                    {{ $handling==='done' ? 'badge-hs-done' : '' }}
                                ">
                                    {{ ucfirst($handling) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('orders.history.show', $o->id) }}"
                                   class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>

                        <tr class="bg-white">
                            <td colspan="6" class="small text-muted">
                                @foreach($o->items as $it)
                                    • {{ $it->name }}
                                      <span class="fw-semibold">({{ $it->size ?? '-' }})</span>
                                      ({{ $it->qty }}x)
                                @endforeach
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
</main>

</body>
</html>
