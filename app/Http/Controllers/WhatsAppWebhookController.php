<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsApp\WhatsAppBotService;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request, WhatsAppBotService $bot)
    {
        $secret = $request->header('X-Webhook-Secret');

        if (! $secret || ! hash_equals((string) config('services.whatsapp.webhook_secret'), $secret)) {

            Log::warning('WA WEBHOOK: percobaan akses ditolak', [
                'ip' => $request->ip(),
            ]);

            return response()->json(['status' => 'unauthorized'], 401);
        }

        $event = $request->input('event');
        $data = $request->input('data');

        if ($event === 'message' && isset($data['message'])) {
            $msg = $data['message'];

            Log::info('=== WA EVENT MASUK (VIA WEBHOOK) ===', [
                'from' => $msg['from'] ?? null,
                'body' => $msg['body'] ?? '',
                'fromMe' => $msg['fromMe'] ?? false,
            ]);

            if (($msg['fromMe'] ?? false) || ($msg['isStatus'] ?? false)) {
                return response()->json(['status' => 'ignored']);
            }

            $from = $msg['from'] ?? '';
            $text = $msg['body'] ?? '';

            if (! empty($text) && ! empty($from)) {
                try {
                    $bot->handle($from, $text);
                } catch (\Throwable $e) {
                    Log::error('WA WEBHOOK BOT ERROR: ' . $e->getMessage(), [
                        'from' => $from,
                        'exception' => $e,
                    ]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
