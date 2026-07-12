<?php

namespace App\Services;

use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS message using the configured gateway.
     */
    public static function send(string $to, string $message): bool
    {
        $settings = HomepageSetting::get('sms_settings', []);

        if (empty($settings['enabled'])) {
            Log::info('SMS sending is disabled in settings.');

            return false;
        }

        $gateway = $settings['gateway'] ?? '';
        $apiKey = $settings['api_key'] ?? '';
        $senderId = $settings['sender_id'] ?? '';

        if (empty($apiKey)) {
            Log::error('SMS API Key/Token is missing in configuration.');

            return false;
        }

        // Clean and format recipient phone number to 11 digits starting with 01
        $phone = preg_replace('/[^0-9]/', '', $to);
        if (str_starts_with($phone, '8801') && strlen($phone) === 13) {
            $phone = substr($phone, 2);
        } elseif (str_starts_with($phone, '01') && strlen($phone) === 11) {
            // Already standard format
        } else {
            if (strlen($phone) === 10 && str_starts_with($phone, '1')) {
                $phone = '0'.$phone;
            }
        }

        try {
            $response = null;
            $timeout = 10;

            switch ($gateway) {
                case 'greenweb':
                    // Greenweb requires 880 prefix
                    $greenwebPhone = str_starts_with($phone, '880') ? $phone : '88'.$phone;
                    $response = Http::timeout($timeout)->get('https://api.greenweb.com.bd/api.php', [
                        'token' => $apiKey,
                        'to' => $greenwebPhone,
                        'message' => $message,
                    ]);
                    break;

                case 'mim_sms':
                    $response = Http::timeout($timeout)->get('https://api.mimsms.com/api/sendsms', [
                        'api_key' => $apiKey,
                        'type' => 'text',
                        'senderid' => $senderId,
                        'contacts' => $phone,
                        'msg' => $message,
                    ]);
                    break;

                case 'bulksmsbd':
                    $response = Http::timeout($timeout)->get('https://bulksmsbd.net/api/smsapi', [
                        'api_key' => $apiKey,
                        'type' => 'text',
                        'senderid' => $senderId,
                        'number' => $phone,
                        'message' => $message,
                    ]);
                    break;

                case 'elitbuzz':
                    $response = Http::timeout($timeout)->get('https://elitbuzz-bd.com/smsapi', [
                        'api_key' => $apiKey,
                        'type' => 'text',
                        'contacts' => $phone,
                        'senderid' => $senderId,
                        'msg' => $message,
                    ]);
                    break;

                default:
                    Log::error("Unknown SMS gateway: {$gateway}");

                    return false;
            }

            if ($response && $response->successful()) {
                Log::info("SMS successfully sent to {$phone} using {$gateway} gateway.");

                return true;
            }

            $status = $response ? $response->status() : 'No Response';
            $body = $response ? $response->body() : '';
            Log::error("Failed to send SMS to {$phone} via {$gateway}. Status: {$status}. Body: {$body}");

            return false;
        } catch (\Exception $e) {
            Log::error('Exception occurred while sending SMS: '.$e->getMessage());

            return false;
        }
    }
}
