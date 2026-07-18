<?php

namespace App\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TelegramLogHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (! $token || ! $chatId) {
            return;
        }

        // Cegah spam: error dengan pesan sama cuma dikirim 1x per 10 menit
        $fingerprint = 'telegram_log_throttle_' . md5($record->message);

        if (Cache::has($fingerprint)) {
            return;
        }

        Cache::put($fingerprint, true, now()->addMinutes(10));

        $message = "🔴 *SIGMA-3 Error*\n"
            . "Level: {$record->level->name}\n"
            . "Pesan: " . mb_substr($record->message, 0, 500) . "\n"
            . "Waktu: " . now()->format('Y-m-d H:i:s');

        try {
            Http::timeout(5)->post(
                "https://api.telegram.org/bot{$token}/sendMessage",
                [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                ]
            );
        } catch (\Throwable $e) {
            // jangan biarkan kegagalan kirim notif ikut menjatuhkan request
        }
    }
}