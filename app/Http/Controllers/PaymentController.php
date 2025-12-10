<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // GET /checkout/payments/{payment}/status
    public function status(Payment $payment)
    {
        $order = Order::findOrFail($payment->order_id);
        abort_unless($order->user_id === auth()->id(), 403);

        return response()->json([
            'payment_status' => $payment->status, // pending / success
            'order_status' => $order->status,
            'redirect_url' => ($payment->status === 'success' && $order->status === 'paid')
                ? route('checkout.success', $order->id)
                : null,
        ]);
    }

    // POST /checkout/payments/{payment}/success
    public function success(Request $request, Payment $payment)
    {
        $order = Order::with('items')->findOrFail($payment->order_id);
        abort_unless($order->user_id === auth()->id(), 403);

        // idempotent: kalau sudah paid, jangan potong stok lagi
        if ($order->status === 'paid') {
            return response()->json([
                'ok' => true,
                'redirect_url' => route('checkout.success', $order->id),
            ]);
        }

        DB::transaction(function () use ($order, $payment) {
            // lock order supaya gak double
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();

            if ($lockedOrder->status === 'paid') {
                return;
            }

            // potong stok berdasarkan order_items
            foreach ($order->items as $item) {
                if (!$item->product_id) continue;

                $product = Product::where('id', $item->product_id)->lockForUpdate()->first();

                if (!$product) {
                    throw new \RuntimeException("Produk tidak ditemukan (ID: {$item->product_id})");
                }

                $qty = (int)$item->qty;

                if ((int)$product->stock < $qty) {
                    throw new \RuntimeException("Stok {$product->name} tidak cukup. Sisa: {$product->stock}");
                }

                $product->decrement('stock', $qty);
            }

            $payment->update(['status' => 'success']);

            $lockedOrder->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        });

        return response()->json([
            'ok' => true,
            'redirect_url' => route('checkout.success', $order->id),
        ]);
    }
}
