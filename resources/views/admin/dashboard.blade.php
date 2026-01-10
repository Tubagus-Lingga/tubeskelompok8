<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin | VIBRANT</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root{
            --brand:#151B54;
            --brand-dark:#0f1640;
            --white:#ffffff;
            --muted:#6b7280;
            --soft-border: rgba(15,23,42,.08);
            --bg:#f6f7fb;
            --card-bg:#ffffff;
        }

        body{
            margin:0;
            background: var(--bg);
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
        }

        /* ==========================================================
           SIDEBAR
        ========================================================== */
        .sidebar{
            width:260px;
            background: var(--brand);
            min-height:100vh;
            color:#fff;
            display:flex;
            flex-direction:column;
            box-shadow:4px 0 20px rgba(15,23,42,.12);
        }

        .sidebar-header{
            padding:1.5rem;
            border-bottom:1px solid rgba(255,255,255,.15);
        }
        .sidebar-header h4{
            font-weight:800;
            letter-spacing:.5px;
        }
        .sidebar-header small{
            opacity:.8;
        }

        /* Sidebar Menu Items */
        .sidebar-menu{
            padding:1rem 0;
        }
        .sidebar-item{
            display:flex;
            align-items:center;
            gap:.75rem;
            padding:.85rem 1.5rem;
            text-decoration:none;
            color:rgba(255,255,255,.85);
            font-weight:600;
            transition:.2s ease;
        }
        .sidebar-item i{
            font-size:1.1rem;
        }

        .sidebar-item:hover{
            background:var(--brand-dark);
            color:#fff;
        }
        .sidebar-item.active{
            background:var(--white);
            color:var(--brand) !important;
            font-weight:800;
            border-left:5px solid var(--brand);
        }

        .sidebar-footer{
            margin-top:auto;
            padding:1rem 1.5rem;
            border-top:1px solid rgba(255,255,255,.2);
        }

        /* ==========================================================
           CONTENT AREA
        ========================================================== */
        .admin-content{
            flex:1;
            padding:2rem;
        }

        .page-title{
            font-weight:800;
            color:var(--brand);
            margin-bottom:1.5rem;
            letter-spacing:.2px;
        }

        @media (max-width:992px){
            .sidebar{
                width:100%;
                position:relative;
                min-height:auto;
            }
        }
    </style>
</head>

<body>

<div style="display:flex; min-height:100vh;">

    {{-- =============================== SIDEBAR =============================== --}}
    <aside class="sidebar">

        <div class="sidebar-header">
            <h4>ADMIN PANEL</h4>
            <small>VIBRANT System</small>
        </div>

        <nav class="sidebar-menu">

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Manajemen Produk
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-check"></i> Pesanan Masuk
            </a>

        </nav>

        {{-- FOOTER --}}
        <div class="sidebar-footer">
            <a href="{{ route('home') }}" class="sidebar-item">
                <i class="bi bi-house"></i> Kembali ke Toko
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-danger w-100 btn-sm fw-bold">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>

    </aside>

    {{-- =============================== CONTENT =============================== --}}
    <main class="admin-content">
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
