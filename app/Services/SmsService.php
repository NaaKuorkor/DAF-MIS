<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{

    public function send($phone, $message)
    {

        $response = Http::withHeaders([
            'api-key' => config('services.ArkeselSms.key'),
        ])->post(config('services.ArkeselSms.url'), [
            'sender'     => 'GEEU',
            'message'    => $message,
            'recipients' => $phone,
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => "Sms sending failed"
            ]);
        }

        return $response->json([
            'success' => true,
            'message' => "Sms sent successfully"
        ]);
    }
}
