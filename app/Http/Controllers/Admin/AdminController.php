<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order; // jika ada model Order
use App\Models\User;  // jika ada model User
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Hitung total data
        $totalProduk = Product::count();
        $pesananBaru = 0; // contoh filter pesanan baru
        $totalUser = User::count();

        return view('admin.dashboard-home', compact('totalProduk', 'pesananBaru', 'totalUser'));
    }
}
