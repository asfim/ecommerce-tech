<?php

namespace App\Services;

use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Http;

class BulkSmsService
{
    public function getBalance()
    {
        $response = Http::asForm()->post(
            'https://bulksmsbd.net/api/getBalanceApi',
            [
                'api_key' => env('BULKSMSBD_API_KEY'),
            ]
        );

        return $response->json();
    }

    public function send($number, $message)
    {
        $settings = HomepageSetting::get('sms_settings', []);

        if (isset($settings['enabled']) && ! $settings['enabled']) {
            return ['response_code' => 0, 'success' => false, 'message' => 'SMS is disabled in admin settings.'];
        }

        $apiKey = ! empty($settings['api_key']) ? $settings['api_key'] : env('BULKSMSBD_API_KEY');
        $senderId = ! empty($settings['sender_id']) ? $settings['sender_id'] : env('BULKSMSBD_SENDER_ID');

        if (empty($apiKey)) {
            return ['response_code' => 0, 'success' => false, 'message' => 'API Key is missing.'];
        }

        $response = Http::asForm()->post(
            'https://bulksmsbd.net/api/smsapi',
            [
                'api_key' => $apiKey,
                'type' => 'text',
                'senderid' => $senderId,
                'number' => $number,
                'message' => $message,
            ]
        );

        return $response->json();
    }
}
