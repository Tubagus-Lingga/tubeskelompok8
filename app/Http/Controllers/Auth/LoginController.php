<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
<<<<<<< HEAD
     * Menampilkan formulir login.
     * Route: Route::get('/login', [LoginController::class, 'create'])->name('login');
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Pastikan view 'login.blade.php' tersedia di resources/views/
        return view('login');
    }

    /**
     * Menangani permintaan otentikasi (login) yang masuk.
     * Route: Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi Input
=======
     * Tampilkan halaman login.
     */
    public function create()
    {
        return view('login'); 
    }

    /**
     * Proses login user.
     */
    public function authenticate(Request $request)
    {
        // Validasi input
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

<<<<<<< HEAD
        // 2. Coba Proses Otentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Otentikasi Berhasil
            $request->session()->regenerate();

            // Redirect pengguna ke halaman yang mereka coba akses sebelumnya, 
            // atau default ke '/' (halaman home).
            return redirect()->intended('/')->with('success', 'Anda berhasil masuk!');
        }

        // 3. Otentikasi Gagal
        // Melempar exception yang akan ditangkap Laravel untuk mengembalikan error validasi ke form
        throw ValidationException::withMessages([
            'email' => __('Email atau Password yang Anda masukkan tidak valid.'),
=======
        // Coba login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Regenerate session (Wajib untuk keamanan)
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect ADMIN
            if ($user->role === 'admin') {
                return redirect()->intended('/admin')
                                 ->with('success', 'Selamat datang Admin!');
            }

            // Redirect USER biasa
            return redirect()->intended('/')
                             ->with('success', 'Login berhasil!');
        }

        // Jika gagal login, kembalikan error
        throw ValidationException::withMessages([
            'email' => 'Email atau password tidak cocok dengan data kami.',
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
        ]);
    }

    /**
<<<<<<< HEAD
     * Logout pengguna.
     * Route: Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna saat ini

        $request->session()->invalidate(); // Invalidasi sesi

        $request->session()->regenerateToken(); // Buat token CSRF baru

        return redirect('/')->with('status', 'Anda telah berhasil keluar.');
=======
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Hancurkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
               ->with('status', 'Anda telah berhasil keluar.');
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
    }
}
