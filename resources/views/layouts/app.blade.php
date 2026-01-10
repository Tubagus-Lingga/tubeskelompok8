<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'VIBRANT')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-black: #000000;
            --brand-white: #ffffff;
            --brand-gray: #f4f4f4;
            --brand-gray-dark: #333333;
            --text-main: #000000;
            --text-muted: #666666;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-white);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 800; /* Bold headings like VIBRANT */
            letter-spacing: -0.02em;
            text-transform: uppercase;
        }

        a { text-decoration: none; color: inherit; }
        a:hover { color: inherit; }

        /* NAVBAR STYLES */
        .navbar {
            background-color: var(--brand-white);
            border-bottom: 1px solid #e5e5e5;
            padding: 1.25rem 0;
            z-index: 1030; /* Ensure on top of other sticky elements */
        }
        .navbar-brand {
            font-weight: 900;
            font-size: 1.5rem;
            letter-spacing: -0.02em;
            color: var(--brand-black) !important;
            text-transform: uppercase;
        }
        .nav-link {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--brand-black) !important;
            margin: 0 1rem;
            transition: opacity 0.2s;
        }
        .nav-link:hover {
            opacity: 0.6;
        }
        .nav-actions .btn {
            border: none;
            background: transparent;
            padding: 0.5rem;
        }
        
        /* BUTTONS */
        .btn-brand {
            background-color: var(--brand-black);
            color: var(--brand-white);
            border: 1px solid var(--brand-black);
            border-radius: 0; /* Square/Sharp */
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
        }
        .btn-brand:hover {
            background-color: transparent;
            color: var(--brand-black);
        }
        
        .btn-outline-brand {
            background-color: transparent;
            color: var(--brand-black);
            border: 1px solid var(--brand-black);
            border-radius: 0;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
        }
        .btn-outline-brand:hover {
            background-color: var(--brand-black);
            color: var(--brand-white);
        }

        /* FORMS */
        .form-control, .form-select {
            border-radius: 0;
            border: 1px solid #e5e5e5;
            padding: 0.75rem;
            font-weight: 500;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: none;
            border-color: var(--brand-black);
        }

        /* FOOTER */
        footer {
            background-color: var(--brand-black);
            color: var(--brand-white);
            padding: 4rem 0 2rem;
            margin-top: auto;
        }
        footer h5 {
            color: var(--brand-white);
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        footer a {
            color: #999;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.2s;
            text-transform: none; /* Keep footer links normal case */
        }
        footer a:hover {
            color: var(--brand-white);
        }

        /* UTILS */
        .bg-gray { background-color: var(--brand-gray); }
        .text-justify { text-align: justify; }

        /* GLOBAL ANIMATIONS */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        body { animation: fadeIn 0.6s ease-out; }

        /* SMOOTH SCROLL */
        html { scroll-behavior: smooth; }

        /* NAVBAR LINK HOVER */
        .nav-link {
            position: relative;
            display: inline-block;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0; height: 2px;
            display: block;
            margin-top: 5px;
            right: 0;
            background: var(--brand-black);
            transition: width 0.3s ease;
            -webkit-transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
            left: 0;
            background: var(--brand-black);
        }

        /* BUTTON AESTHETICS */
        .btn-brand {
            position: relative;
            overflow: hidden;
            z-index: 1;
            transition: color 0.3s ease;
        }
        .btn-brand::before {
            content: "";
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background-color: var(--brand-white);
            transform: scaleX(0);
            transform-origin: 0 50%;
            transition: transform 0.3s ease-out;
            z-index: -1;
        }
        .btn-brand:hover { color: var(--brand-black); }
        .btn-brand:hover::before { transform: scaleX(1); }

        .btn-outline-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* CUSTOM SCROLLBAR */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }

        /* FOOTER EXTRAS */
        footer a:hover {
            color: var(--brand-white);
            transform: translateX(5px);
            display: inline-block;
        }
        /* MARQUEE STREAMER */
        .marquee-container {
            background: #000;
            color: #fff;
            padding: 0.8rem 0;
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            z-index: 1020; /* Lower than navbar (1030) so it scrolls under if needed */
            border-bottom: 1px solid #333;
        }
        .marquee-content {
            display: inline-block;
            animation: marquee 20s linear infinite;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.9rem;
        }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

    </style>
    @yield('styles')
</head>
<body>



    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                VIBRANT
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav Actions (Always Visible) -->
            <div class="nav-actions d-flex align-items-center gap-3 order-lg-2 ms-auto">
                <!-- Search Icon (Optional/Placeholder) -->
                <a href="{{ route('katalog') }}" title="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </a>

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" title="Cart" class="position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
                        <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
                    </svg>
                    <span id="global-cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark" style="font-size: 0.6rem;">
                        0
                    </span>
                </a>

                <!-- User / Auth -->
                @auth
                    <div class="dropdown">
                        <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                            </svg>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end rounded-0 border-0 shadow-sm mt-3">
                            <li><a class="dropdown-item" href="#">Halo, {{ Auth::user()->name }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.history') }}">Riwayat Pesanan</a></li>
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-uppercase fw-bold" style="font-size: 0.8rem;">Login</a>
                @endauth
            </div>

            <!-- Collapsible Menu (Home & Shop only) -->
            <div class="collapse navbar-collapse order-lg-1" id="navMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0" style="margin-left: 2.5rem;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('katalog') }}">Shop</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MARQUEE (Relocated) -->
    <div class="marquee-container">
        <div class="marquee-content">
            NEW ARRIVALS • WORLDWIDE DELIVERY • SECURE PAYMENT • NEW ARRIVALS • WORLDWIDE DELIVERY • SECURE PAYMENT • NEW ARRIVALS • WORLDWIDE DELIVERY • SECURE PAYMENT • NEW ARRIVALS • WORLDWIDE DELIVERY • SECURE PAYMENT • 
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">VIBRANT</h5>
                    <p class="text-secondary small">
                        Raw energy from the underground. Define your style with our exclusive drops.
                    </p>
                    <p class="text-secondary small">&copy; 2025 Vibrant. All rights reserved.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Shop</h5>
                    <a href="{{ route('katalog') }}">All Products</a>
                    <a href="{{ route('katalog') }}?category=kaos">T-Shirts</a>
                    <a href="{{ route('katalog') }}?category=hoodie">Hoodies</a>
                    <a href="{{ route('katalog') }}?category=celana">Pants</a>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Support</h5>
                     <a href="https://wa.me/62895370401796" target="_blank">Contact Us</a>
                     <a href="{{ route('orders.history') }}">Order Status</a>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Newsletter</h5>
                    <p class="text-secondary small">Subscribe to get the latest drops.</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="ENTER YOUR EMAIL">
                        <button class="btn btn-light" type="button">→</button>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global Cart Count Script
        document.addEventListener('DOMContentLoaded', () => {
            const CART_KEY = 'tubes_cart_v1';
            function updateGlobalCartCount(){
                const cart = JSON.parse(localStorage.getItem(CART_KEY) || '[]');
                const total = cart.reduce((sum, i) => sum + (i.qty || 1), 0);
                const el = document.getElementById('global-cart-count');
                if(el) el.textContent = total;
            }
            updateGlobalCartCount();
        });
    </script>
    @yield('scripts')
    <!-- GLOBAL MUSIC PLAYER -->
    <audio id="bgMusic" loop>
        <source src="{{ asset('asset/song.mp3') }}" type="audio/mp3">
    </audio>

    <div id="musicControl" class="music-control" title="Toggle Music">
        <div class="music-bars">
            <span></span><span></span><span></span>
        </div>
    </div>

    <style>
        .music-control {
            position: fixed;
            bottom: 2rem;
            left: 2rem;
            z-index: 9999;
            width: 50px;
            height: 50px;
            background: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            border: 2px solid #fff;
            transition: transform 0.2s;
        }
        .music-control:hover { transform: scale(1.1); }
        .music-control.playing .music-bars span {
            animation: sound 1s linear infinite alternate;
        }
        .music-bars {
            display: flex;
            align-items: flex-end;
            gap: 3px;
            height: 20px;
        }
        .music-bars span {
            width: 3px;
            height: 100%;
            background: #fff;
            display: block;
            animation: none;
            height: 5px;
        }
        @keyframes sound {
            0% { height: 5px; }
            100% { height: 20px; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const music = document.getElementById('bgMusic');
            const btn = document.getElementById('musicControl');
            const STORAGE_KEY_TIME = 'tubes_music_time';
            const STORAGE_KEY_STATUS = 'tubes_music_status';

            // RESTORE STATE
            const savedTime = localStorage.getItem(STORAGE_KEY_TIME);
            const savedStatus = localStorage.getItem(STORAGE_KEY_STATUS);

            if (savedTime) music.currentTime = parseFloat(savedTime);
            
            if (savedStatus === 'playing') {
                music.play().then(() => {
                    btn.classList.add('playing');
                }).catch(e => {
                    console.log("Autoplay blocked, waiting for interaction");
                    btn.classList.remove('playing');
                });
            }

            // TOGGLE PLAY
            btn.addEventListener('click', function() {
                if (music.paused) {
                    music.play();
                    btn.classList.add('playing');
                    localStorage.setItem(STORAGE_KEY_STATUS, 'playing');
                } else {
                    music.pause();
                    btn.classList.remove('playing');
                    localStorage.setItem(STORAGE_KEY_STATUS, 'paused');
                }
            });

            // SAVE STATE ON UNLOAD
            window.addEventListener('beforeunload', function() {
                localStorage.setItem(STORAGE_KEY_TIME, music.currentTime);
            });
            
            // PERIODIC SAVE (in case of crash/nav)
            setInterval(() => {
                if(!music.paused) {
                    localStorage.setItem(STORAGE_KEY_TIME, music.currentTime);
                }
            }, 1000);
        });
    </script>
</body>
</html>
