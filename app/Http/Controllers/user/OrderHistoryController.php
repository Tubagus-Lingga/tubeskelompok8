<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $orders = Order::with(['items', 'payment'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('orders.history-index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load(['items.product', 'payment']);

        return view('orders.history-show', compact('order'));
    }
}
