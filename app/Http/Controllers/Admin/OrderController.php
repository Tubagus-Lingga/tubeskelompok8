<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['payment','user'])
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product','payment','user']);
        return view('admin.orders.show', compact('order'));
    }

    // ✅ UPDATE STATUS PENANGANAN
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'handling_status' => 'required|in:new,processing,shipped,done'
        ]);

        $order->update([
            'handling_status' => $request->handling_status
        ]);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Status penanganan berhasil diperbarui!');
    }
}
