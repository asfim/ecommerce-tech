<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Raziul\Sslcommerz\Facades\Sslcommerz;

class SslcommerzController extends Controller
{
    /**
     * Handle the successful payment callback.
     */
    public function success(Request $request): RedirectResponse
    {
        $tranId = $request->input('tran_id');

        if (empty($tranId)) {
            return redirect()->route('checkout')->with('error', 'Transaction ID is missing.');
        }

        $order = Order::where('invoice_no', $tranId)->first();

        if (! $order) {
            return redirect()->route('checkout')->with('error', 'Order not found.');
        }

        $isValid = Sslcommerz::validatePayment($request->all(), $order->invoice_no, (float) $order->total);

        if ($isValid) {
            $order->update([
                'payment_status' => 'paid',
            ]);

            return redirect()->route('order.invoice', $order->invoice_no)->with('success', 'Payment was successful!');
        }

        return redirect()->route('order.invoice', $order->invoice_no)->with('error', 'Payment verification failed.');
    }

    /**
     * Handle the failed payment callback.
     */
    public function failure(Request $request): RedirectResponse
    {
        $tranId = $request->input('tran_id');

        if (! empty($tranId)) {
            $order = Order::where('invoice_no', $tranId)->first();
            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                ]);
            }
        }

        return redirect()->route('checkout')->with('error', 'Payment failed. Please try again.');
    }

    /**
     * Handle the cancelled payment callback.
     */
    public function cancel(Request $request): RedirectResponse
    {
        $tranId = $request->input('tran_id');

        if (! empty($tranId)) {
            $order = Order::where('invoice_no', $tranId)->first();
            if ($order) {
                $order->update([
                    'payment_status' => 'cancelled',
                ]);
            }
        }

        return redirect()->route('checkout')->with('warning', 'Payment was cancelled.');
    }

    /**
     * Handle the IPN (Instant Payment Notification) callback.
     */
    public function ipn(Request $request): Response
    {
        $tranId = $request->input('tran_id');

        if (! empty($tranId)) {
            $order = Order::where('invoice_no', $tranId)->first();

            if ($order && $order->payment_status !== 'paid') {
                $isValid = Sslcommerz::validatePayment($request->all(), $order->invoice_no, (float) $order->total);

                if ($isValid) {
                    $order->update([
                        'payment_status' => 'paid',
                    ]);
                }
            }
        }

        return response('IPN Handled', 200);
    }
}
