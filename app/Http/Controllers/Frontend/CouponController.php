<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', $validated['code'])->first();

        if (! $coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid discount code.',
            ], 422);
        }

        if (! $coupon->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'This discount code is inactive.',
            ], 422);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'This discount code has expired.',
            ], 422);
        }

        if ($validated['subtotal'] < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount to apply this code is ৳'.number_format($coupon->min_order_amount, 2),
            ], 422);
        }

        $discount = $coupon->calculateDiscount($validated['subtotal']);

        return response()->json([
            'success' => true,
            'code' => $coupon->code,
            'discount' => $discount,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'message' => 'Coupon applied successfully!',
        ]);
    }
}
