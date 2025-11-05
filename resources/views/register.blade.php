<!doctype html>

<html lang="id">

<head>

  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width,initial-scale=1" />

  <title>TubesBrand — Register</title>

  <!-- Memuat Bootstrap 5 -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>

    :root{ --brand:#151B54; }

    /* CSS ASLI (TIDAK DIUBAH) */

    body{ background:#f6f7fb; font-family:Inter, Arial; }

    .card{ max-width:600px; margin:3.5rem auto; }

    .btn-brand{ background:var(--brand); color:#fff; border:none; }

  </style>

</head>

<body>

  <!-- Class card menggunakan shadow-sm seperti file asli -->

  <div class="card shadow-sm"> 

    <div class="card-body">

      <h4 class="card-title mb-3">Daftar Akun Baru</h4>



      <div id="msg">

        <!-- Menampilkan pesan sukses setelah register (dari controller) -->

        @if (session('status'))

            <div class="alert alert-success">{{ session('status') }}</div>

        @endif

        <!-- Menampilkan pesan error validasi (dari Laravel) -->

        @if ($errors->any())

            <div class="alert alert-danger">Pendaftaran gagal. Mohon periksa input Anda.</div>

        @endif

      </div>



      <!-- FORM POST KE ROUTE REGISTER.STORE -->

      <form method="POST" action="{{ route('register.store') }}">

        @csrf 



        <!-- g-2 (gutter 2) seperti file asli -->

        <div class="row g-2"> 

          <!-- Nama Lengkap (Baris Penuh) -->

          <!-- Tambahkan mb-3 untuk memberikan jarak ke baris input di bawahnya, seperti yang terjadi secara alami di HTML statis -->

          <div class="col-12 mb-3"> 

            <label class="form-label small">Nama Lengkap</label>

            <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Anda" value="{{ old('name') }}" required>

            <!-- Hapus mt-1 pada error message agar tidak menambah jarak vertikal -->

            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror

          </div>

          

          <!-- Email (Setengah Baris) -->

          <div class="col-12 col-md-6"> 

            <label class="form-label small">Email</label>

            <input id="email" name="email" class="form-control @error('email') is-invalid @enderror" type="email" placeholder="you@example.com" value="{{ old('email') }}" required>

            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror

          </div>

          

          <!-- Password (Setengah Baris) -->

          <div class="col-12 col-md-6"> 

            <label class="form-label small">Password</label>

            <input id="password" name="password" class="form-control @error('password') is-invalid @enderror" type="password" placeholder="••••••" required>

            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror

          </div>

        </div>



        <!-- Tetap menggunakan mt-3 -->

        <div class="d-flex justify-content-between align-items-center mt-3">

          <!-- Tombol Daftar -->

          <button type="submit" class="btn btn-brand">Daftar</button>

          <!-- Link Masuk menggunakan route helper -->

          <a href="{{ route('login') }}" class="text-decoration-none small text-muted">Sudah punya akun? Masuk</a>

        </div>

      </form>

    </div>

  </div>

</body>

</html>