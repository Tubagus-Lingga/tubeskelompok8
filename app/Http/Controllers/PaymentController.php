<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function success(Request $request, Payment $payment)
    {
        DB::transaction(function () use ($payment, $request) {
            $payment->refresh();
            $order = $payment->order()->lockForUpdate()->first();

            abort_unless($order->user_id === auth()->id(), 403);

            if ($payment->status === 'success' && $order->status === 'paid') {
                return;
            }

            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
                'payload' => $request->all(),
            ]);

            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        });

        return redirect()->route('checkout.success', $payment->order_id);
    }
}
