<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin | TubesBrand</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-color: #151B54;
            --brand-color-dark: #0f1640;
        }

        /* Layout */
        .admin-wrapper { display: flex; min-height: 100vh; background-color: #f4f5f7; }

        /* Sidebar */
        .sidebar { width: 260px; background-color: var(--brand-color); color: #fff; padding: 1rem 0; }
        .sidebar-header { padding: 0 1.5rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.25); }
        .sidebar-item { display: block; padding: 0.75rem 1.5rem; color: rgba(255,255,255,0.8); text-decoration: none; transition: .2s; }
        .sidebar-item:hover, .sidebar-item.active { background-color: var(--brand-color-dark); color: #fff; }

        /* Content */
        .content { flex-grow: 1; padding: 2rem; }

        @media (max-width: 992px) { .sidebar { width: 100%; position: relative; } .sidebar-item { text-align: center; } }
    </style>
</head>

<body>
<div class="admin-wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="fw-bold text-white mb-0">ADMIN PANEL</h4>
            <small class="text-light">TubesBrand System</small>
        </div>

        <nav class="mt-3">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}" class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Manajemen Produk
            </a>

            <div class="mt-4 border-top border-secondary pt-3 px-3">
                <a href="{{ route('home') }}" class="sidebar-item">
                    <i class="bi bi-house"></i> Kembali ke Toko
                </a>

                <form method="POST" action="{{ route('logout') }}" class="d-grid mt-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- KONTEN DINAMIS -->
    <main class="content">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
