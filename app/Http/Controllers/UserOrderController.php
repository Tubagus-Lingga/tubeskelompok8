<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product','payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.history-index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load(['items.product','payment','user']);

        return view('orders.history-show', compact('order'));
    }
}
