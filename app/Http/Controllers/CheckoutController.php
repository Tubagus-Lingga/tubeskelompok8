<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // GET /checkout
    public function index()
    {
        $products = Product::select('id', 'name', 'price')->orderBy('name')->get();
        return view('checkout', compact('products'));
    }

    // POST /checkout/process
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $userId = auth()->id();

        $order = DB::transaction(function () use ($data, $userId) {
            $code = 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));

            $productIds = collect($data['items'])->pluck('product_id')->unique()->values();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($productIds as $pid) {
                abort_unless($products->has($pid), 422, "Product ID {$pid} tidak ditemukan");
            }

            $total = 0;
            foreach ($data['items'] as $item) {
                $p = $products[$item['product_id']];
                $total += ((int)$item['qty']) * ((int)$p->price);

            }

            $order = Order::create([
                'user_id' => $userId,
                'code' => $code,
                'total_amount' => $total,
                'status' => 'pending_payment',
            ]);

            foreach ($data['items'] as $item) {
                $p = $products[$item['product_id']];
                $qty = (int) $item['qty'];
                $price = (int) $p->price;

                $order->items()->create([
                    'product_id' => $p->id,
                    'name' => $p->name,
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $qty * $price,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'provider' => 'manual',
                'amount' => $total,
                'status' => 'pending',
            ]);

            return $order->load('items', 'payment');
        });

        return response()->json([
            'message' => 'Checkout created',
            'order' => $order,
            'payment_success_url' => route('checkout.payments.success', $order->payment->id),
            'success_page_url' => route('checkout.success', $order->id),
        ]);
    }

    // GET /checkout/success/{order}
    public function success(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->status === 'paid', 403);

        $order->load('items', 'payment');
        return view('checkout.success', compact('order'));
    }
}
