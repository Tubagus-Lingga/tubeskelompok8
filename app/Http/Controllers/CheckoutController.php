<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private const SHIPPING_FEE = 15000;

    // GET /checkout
    public function index()
    {
        return view('checkout');
    }

    // POST /checkout/process
    public function store(Request $request)
    {
        $data = $request->validate([
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.name'  => ['required', 'string'],
            'cart.*.price' => ['required', 'numeric', 'min:1'],
            'cart.*.qty'   => ['required', 'integer', 'min:1'],
            'cart.*.id'    => ['nullable'],
            'cart.*.product_id' => ['nullable'],

            // ✅ wajib size dari localStorage
            'cart.*.size' => ['required', 'string', 'max:10'],

            'shipping' => ['sometimes', 'array'],
            'shipping.alamat' => ['required', 'string'],
            'shipping.city' => ['required', 'string'],
            'shipping.district' => ['required', 'string'],
            'shipping.postal_code' => ['required', 'string'],
            'shipping.telp'   => ['required', 'string'],

            'payment' => ['sometimes', 'array'],
        ]);

        $cart = $data['cart'];
        $userId = Auth::id();
        $shippingFee = self::SHIPPING_FEE;

        /** @var \App\Models\Order $order */
        $order = DB::transaction(function () use ($cart, $userId, $shippingFee, $data) {

            $code = 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));

            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += ((int)$item['qty']) * ((int)$item['price']);
            }

            $total = $subtotal + ($subtotal > 0 ? $shippingFee : 0);

            // 1) buat order
            $order = Order::create([
                'user_id' => $userId,
                'code' => $code,
                'total_amount' => $total,
                'status' => 'pending_payment',
                'handling_status' => 'new',

                // ✅ alamat detail
                'alamat' => $data['shipping']['alamat'] ?? null,
                'city' => $data['shipping']['city'] ?? null,
                'district' => $data['shipping']['district'] ?? null,
                'postal_code' => $data['shipping']['postal_code'] ?? null,
                'telp'   => $data['shipping']['telp'] ?? null,
            ]);

            // 2) proses tiap item cart
            foreach ($cart as $item) {
                $productId = $item['product_id'] ?? $item['id'] ?? null;
                $size = $item['size'];
                $qty  = (int)$item['qty'];

                if(!$productId){
                    throw new \Exception("Produk tidak valid.");
                }

                // ambil produk
                $product = Product::findOrFail($productId);

                // 3) ambil variant sesuai size + lock biar aman
                $variant = ProductVariant::where('product_id', $productId)
                    ->where('size', $size)
                    ->lockForUpdate()
                    ->first();

                if(!$variant){
                    throw new \Exception("Ukuran $size tidak tersedia untuk {$product->name}.");
                }

                if($variant->stock < $qty){
                    throw new \Exception("Stok {$product->name} ukuran $size tidak cukup.");
                }

                // 4) kurangi stok variant
                $variant->decrement('stock', $qty);

                // 5) simpan order item (WAJIB simpan size)
                $order->items()->create([
                    'product_id' => $productId,
                    'name' => $product->name,
                    'size' => $size,
                    'qty' => $qty,
                    'price' => (int)$product->price,
                    'subtotal' => $qty * (int)$product->price,
                ]);
            }

            // 6) buat payment
            Payment::create([
                'order_id' => $order->id,
                'provider' => 'manual',
                'amount' => $total,
                'status' => 'pending',
                'payload' => [
                    'cart' => $cart
                ],
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
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items', 'payment');
        $qrSrc = '/images/qr-placeholder.png';

        return view('checkout.pay', compact('order', 'qrSrc'));
    }

    // GET /checkout/success/{order}
    public function success(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($order->status === 'paid', 403);

        $order->load('items', 'payment');

        return view('checkout.success', compact('order'));
    }
}
