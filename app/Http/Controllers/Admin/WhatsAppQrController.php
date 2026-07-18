<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class WhatsAppQrController extends Controller
{
    public function show()
    {
        $sessionId = 'default';

        $response = Http::withToken(config('services.whatsapp.token'))
            ->timeout(30)
            ->post(
                'http://' . config('services.whatsapp.host') . '/sessions/' . $sessionId . '/start'
            );

        return view('admin.whatsapp-qr', [
            'httpCode' => $response->status(),
            'data' => $response->json(),
        ]);
    }
}