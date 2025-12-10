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

    // ✅ Pesanan baru: order yang sudah dibayar
    // (sesuai flow kamu: saat sukses, order->status jadi 'paid')
    $pesananBaru = Order::where('status', 'paid')->count();

    $totalUser = User::count();

    return view('admin.dashboard-home', compact('totalProduk', 'pesananBaru', 'totalUser'));
}

}
