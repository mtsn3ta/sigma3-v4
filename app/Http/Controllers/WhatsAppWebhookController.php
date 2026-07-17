<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsApp\WhatsAppBotService;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request, WhatsAppBotService $bot)
    {
        $event = $request->input('event');
        $data = $request->input('data');

        if ($event === 'message' && isset($data['message'])) {
            $msg = $data['message'];

            // Catat log untuk memastikan data dari VPS benar-benar masuk ke hosting
            Log::info('=== WA EVENT MASUK (VIA WEBHOOK) ===', [
                'from' => $msg['from'],
                'body' => $msg['body'] ?? '',
                'fromMe' => $msg['fromMe']
            ]);

            // Abaikan jika pesan berasal dari bot itu sendiri atau dari status
            if ($msg['fromMe'] || $msg['isStatus']) {
                return response()->json(['status' => 'ignored']);
            }

            $phone = str_replace('@c.us', '', $msg['from']);
            $text = $msg['body'] ?? '';

            // Jalankan bot otomatis andalanmu
            if (!empty($text)) {
                $bot->handle($phone, $text);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}