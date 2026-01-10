<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        // =========================
        // 1) Total Produk
        // =========================
        $totalProduk = Product::count();

        // =========================
        // 2) Pesanan Baru
        // =========================
        // Definisi pesanan baru:
        // sudah dibayar tapi belum diproses admin
        $pesananBaru = Order::where('status', 'paid')
            ->where('handling_status', 'new')
            ->count();

        // =========================
        // 3) Total User
        // =========================
        $totalUser = User::count();

        // =========================
        // 4) Pesanan Terbaru (limit 5)
        // =========================
        $latestOrders = Order::with(['user', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard-home', compact(
            'totalProduk',
            'pesananBaru',
            'totalUser',
            'latestOrders'
        ));
    }
}
