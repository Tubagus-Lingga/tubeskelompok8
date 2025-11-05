<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan mengimpor Model User

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Otentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // 3. Logika Pengalihan Berdasarkan Role
            if ($user->role === User::ROLE_ADMIN) {
                // Redirect ke Dashboard Admin
                return redirect()->intended(route('admin.dashboard')); 
            }

            // Redirect ke Halaman Utama/Pelanggan
            return redirect()->intended(route('home'));
        }

        // 4. Otentikasi Gagal
        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');
    }
}