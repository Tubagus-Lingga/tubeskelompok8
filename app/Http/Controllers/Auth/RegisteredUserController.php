<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Penting: Pastikan Anda mengimpor Model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran (GET /register).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Menampilkan view yang berisi form pendaftaran
        return view('register'); 
    }

    /**
     * Menangani permintaan pendaftaran yang masuk (POST /register).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // Pastikan email unik
            // 'confirmed' memastikan ada field 'password_confirmation' yang cocok
            'password' => ['required', 'confirmed', Rules\Password::defaults()], 
        ], [
            // Pesan kustom untuk user
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
            'required' => ':attribute wajib diisi.',
            // Anda bisa menambahkan pesan validasi lain di sini
        ]);

        // 2. Buat Pengguna Baru di Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi Password (WAJIB)
        ]);

        // 3. Otomatis Login Pengguna (Opsional, tapi umum dilakukan)
        Auth::login($user);

        // 4. Redirect ke halaman setelah pendaftaran berhasil
        return redirect()->intended('/')->with('success', 'Pendaftaran berhasil! Selamat datang di TubesBrand.');
    }
}