<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Pastikan mengimpor Model User
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'], // Atau tambahkan Rules\Password::defaults()
        ]);

        // 2. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan', // 🔑 Kunci Utama: Tetapkan role default
        ]);

        // 3. Opsional: Langsung Login setelah Register (bisa dihilangkan)
        // Auth::login($user); 

        // 4. Redirect
        // Redirect ke halaman login agar user dapat login dengan akun barunya
        return redirect(route('login'))->with('status', 'Pendaftaran berhasil! Silakan masuk.');
    }
}