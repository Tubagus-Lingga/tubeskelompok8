<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private const SHIPPING_FEE = 20000;

    public function index()
    {
        // checkout page milik frontend (alamat + metode bayar)
        // data cart diambil dari localStorage (frontend), jadi di sini cukup tampilkan view
        return view('checkout');
    }

    // POST /checkout/process
    // POST /checkout/process
public function store(Request $request)
{
    $data = $request->validate([
        'cart' => ['required', 'array', 'min:1'],
        'cart.*.name' => ['required', 'string'],
        'cart.*.price' => ['required', 'numeric', 'min:1'],
        'cart.*.qty' => ['required', 'integer', 'min:1'],
        'cart.*.id' => ['nullable'],
        'cart.*.product_id' => ['nullable'],

        // optional (buat disimpan/cek nanti), aman walau belum ada kolom
        'shipping' => ['sometimes', 'array'],
        'payment' => ['sometimes', 'array'],
    ]);

    $cart = $data['cart'];
    $userId = auth()->id();
    $shippingFee = 20000;

    $order = DB::transaction(function () use ($cart, $userId, $shippingFee) {
        $code = 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ((int)$item['qty']) * ((int)$item['price']);
        }

        $total = $subtotal + ($subtotal > 0 ? $shippingFee : 0);

        $order = Order::create([
            'user_id' => $userId,
            'code' => $code,
            'total_amount' => $total,
            'status' => 'pending_payment',
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'] ?? $item['id'] ?? null,
                'name' => $item['name'],
                'qty' => (int)$item['qty'],
                'price' => (int)$item['price'],
                'subtotal' => ((int)$item['qty']) * ((int)$item['price']),
            ]);
        }
        Payment::create([
            'order_id' => $order->id,
            'provider' => 'qris',
            'amount' => $total,
            'status' => 'pending',
        ]);


        return $order->load('payment');
    });

    return response()->json([
        'ok' => true,
        'redirect_url' => route('checkout.pay', $order->id),
    ]);
}

    // GET /checkout/pay/{order}
    public function pay(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items', 'payment');

        // QR placeholder (kamu ganti sendiri)
        $qrSrc = '/images/qr-placeholder.png';

        return view('checkout.pay', compact('order', 'qrSrc'));
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
