<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SmsSettingController extends Controller
{
    public function index(): View
    {
        $settings = HomepageSetting::get('sms_settings', [
            'enabled' => false,
            'gateway' => 'mim_sms',
            'api_key' => '',
            'sender_id' => '',
            'message_template' => 'Dear {customer_name}, your order {invoice_no} has been delivered successfully. Total amount: {total_amount} TK.',
        ]);

        return view('backend.settings.sms', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enabled' => 'nullable|boolean',
            'gateway' => 'required|string|in:mim_sms,greenweb,elitbuzz,bulksmsbd',
            'api_key' => 'required|string',
            'sender_id' => 'nullable|string',
            'message_template' => 'required|string',
        ]);

        // If enabled is not present in request (unchecked checkbox), set it to false
        $validated['enabled'] = (bool) $request->input('enabled', false);

        HomepageSetting::set('sms_settings', $validated);

        return back()->with('success', 'SMS gateway configuration updated successfully.');
    }
}
