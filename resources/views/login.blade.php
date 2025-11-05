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
  </style>
</head>
<body>
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="card-title mb-3">Masuk ke TubesBrand</h4>

      <div id="msg"></div>

      <div class="mb-3">
        <label class="form-label small">Email</label>
        <input id="email" class="form-control" type="email" placeholder="you@example.com">
      </div>
      <div class="mb-3">
        <label class="form-label small">Password</label>
        <input id="password" class="form-control" type="password" placeholder="••••••">
      </div>

      <div class="d-flex justify-content-between align-items-center">
        <button id="btnLogin" class="btn btn-brand">Masuk</button>
        <a href="register">Belum punya akun? Daftar</a>
      </div>
    </div>
  </div>

  <script>
    function showMsg(text, type='danger'){
      document.getElementById('msg').innerHTML = <div class="alert alert-${type}">${text}</div>;
    }

    // Simple client-side users store (demo only)
    function readUsers(){ try{ return JSON.parse(localStorage.getItem('tubes_users') || '[]'); }catch(e){return[];} }
    function saveAuth(auth){ localStorage.setItem('tubes_auth', JSON.stringify(auth)); }

    document.getElementById('btnLogin').addEventListener('click', ()=>{
      const email = document.getElementById('email').value.trim().toLowerCase();
      const pw = document.getElementById('password').value;
      if(!email || !pw) { showMsg('Isi email & password.'); return; }
      const users = readUsers();
      const u = users.find(x => x.email === email && x.password === pw);
      if(!u) { showMsg('Email atau password salah.'); return; }
      // store simple auth
      saveAuth({ email: u.email, name: u.name || '', loggedAt: Date.now() });
      showMsg('Login berhasil. Mengalihkan...', 'success');
      setTimeout(()=> window.location.href = 'home', 900);
    });
  </script>
</body>
</html>