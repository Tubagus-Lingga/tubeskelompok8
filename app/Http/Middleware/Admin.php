<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // <- WAJIB ADA

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cek apakah user role = admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses sebagai admin.');
        }

        return $next($request);
    }
}
