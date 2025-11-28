<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>TubesBrand — Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root{ --brand:#151B54; }
        body{ background:#f6f7fb; font-family:Inter, Arial; }
        .card{ max-width:600px; margin:3.5rem auto; }
        .btn-brand{ background:var(--brand); color:#fff; border:none; }
        .btn-brand:hover{ background:#0f1640; color:#fff; }
    </style>

</head>

<body>

    <div class="card shadow-sm"> 
        <div class="card-body">
            <h4 class="card-title mb-3">Daftar Akun Baru</h4>
            <div id="msg">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">Pendaftaran gagal. Mohon periksa input Anda.</div>
                @endif
            </div>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf 
                <div class="row g-2"> 
                    
                    <div class="col-12 mb-3"> 
                        <label class="form-label small">Nama Lengkap</label>
                        <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Anda" value="{{ old('name') }}" required>
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6 mb-3"> 
                        <label class="form-label small">Email</label>
                        <input id="email" name="email" class="form-control @error('email') is-invalid @enderror" type="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6 mb-3"> 
                        <label class="form-label small">Password</label>
                        <input id="password" name="password" class="form-control @error('password') is-invalid @enderror" type="password" placeholder="••••••" required>
                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 col-md-6 mb-3"> 
                        <label class="form-label small">Konfirmasi Password</label>
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-control" 
                            type="password" 
                            placeholder="••••••" 
                            required>
                    </div>

                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="submit" class="btn btn-brand">Daftar</button>
                    <a href="{{ route('login') }}" class="text-decoration-none small text-muted">Sudah punya akun? Masuk</a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
