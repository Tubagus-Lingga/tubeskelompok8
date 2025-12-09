<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>TubesBrand — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{ --brand:#151B54; }
        body{ background:#f6f7fb; font-family:Inter, Arial; }
        .card{ max-width:520px; margin:4rem auto; }
        .btn-brand{ background:var(--brand); color:#fff; border:none; }
        .btn-brand:hover{ background:#0f1640; color:#fff; }
    </style>
</head>
<body>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title mb-3">Masuk ke TubesBrand</h4>

            <div id="msg">
                @if (session('success') || session('status'))
                    <div class="alert alert-success">{{ session('success') ?? session('status') }}</div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        Email atau password yang Anda masukkan tidak valid.
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf 

                <div class="mb-3">
                    <label class="form-label small">Email</label>
                    <input 
                        id="email" 
                        name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        type="email" 
                        placeholder="you@example.com" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label small">Password</label>
                    <input 
                        id="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        type="password" 
                        placeholder="••••••" 
                        required>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label class="form-check-label small" for="remember">Ingat Saya</label>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-brand">Masuk</button>
                    <a href="{{ route('register') }}" class="small text-decoration-none">Belum punya akun? Daftar</a>
                </div>
            </form>
            </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
