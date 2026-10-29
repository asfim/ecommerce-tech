<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourierSettingController extends Controller
{
    public function index(): View
    {
        $fraudSettings = HomepageSetting::get('courier_fraud_settings', [
            'cancel_threshold' => 2,
            'cancel_rate_threshold' => 40,
            'auto_block_ip' => true,
        ]);

        return view('backend.settings.courier', compact('fraudSettings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cancel_threshold' => 'required|integer|min:1|max:50',
            'cancel_rate_threshold' => 'required|numeric|min:1|max:100',
            'auto_block_ip' => 'nullable|boolean',
        ]);

        HomepageSetting::set('courier_fraud_settings', [
            'cancel_threshold' => (int) $validated['cancel_threshold'],
            'cancel_rate_threshold' => (float) $validated['cancel_rate_threshold'],
            'auto_block_ip' => $request->has('auto_block_ip'),
        ]);

        return back()->with('success', 'Order Cancellation & Fraud Detection Rules updated successfully.');
    }
}
