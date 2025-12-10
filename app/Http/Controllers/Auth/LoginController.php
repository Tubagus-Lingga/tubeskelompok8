<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

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
        ]);
    }

    /**
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
    }
}
