<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Pesanan #{{ $order->id }} — Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar disingkat/dianggap sudah disertakan -->
        @include('admin.dashboard')

        <div class="flex-grow-1 p-4">
            <h1 class="mb-4">Detail Pesanan #{{ $order->id }}</h1>
            
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Informasi Dasar</h5>
                    <p><strong>Status:</strong> <span class="badge bg-success">{{ $order->status }}</span></p>
                    <p><strong>Total Pembayaran:</strong> Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                    <p><strong>Tanggal Pesan:</strong> {{ date('d M Y') }}</p>
                    <hr>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← Kembali ke Daftar Pesanan</a>
                </div>
            </div>

            <h2 class="h5">Produk yang Dipesan</h2>
            <div class="alert alert-info">
                Anda perlu mengimplementasikan logika untuk menampilkan item/produk yang termasuk dalam pesanan ini (memerlukan Model OrderItem).
            </div>
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>