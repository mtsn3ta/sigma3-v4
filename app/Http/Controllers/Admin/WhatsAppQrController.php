<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class WhatsAppQrController extends Controller
{
    public function show()
    {
        $sessionId = 'default';

        $host = config('services.whatsapp.host');
        if (! str_contains($host, ':')) {
            $host = $host . ':' . config('laravel-whatsapp.web.port', 3000);
        }

        try {
            $response = Http::withToken(config('services.whatsapp.token'))
                ->timeout(30)
                ->post(
                    'http://' . $host . '/sessions/' . $sessionId . '/start'
                );

            return view('admin.whatsapp-qr', [
                'httpCode' => $response->status(),
                'data' => $response->json(),
            ]);
        } catch (\Throwable $e) {
            return view('admin.whatsapp-qr', [
                'httpCode' => 500,
                'data' => [
                    'error' => 'Gagal menghubungi WhatsApp sidecar pada http://' . $host,
                    'message' => $e->getMessage(),
                ],
            ]);
        }
    }
}