<?php

namespace App\Listeners;

use App\Services\WhatsApp\WhatsAppBotService;
use Illuminate\Support\Facades\Log;
use Kstmostofa\LaravelWhatsApp\Events\Web\MessageReceived;

class HandleIncomingMessage
{
    public function __construct(
        protected WhatsAppBotService $bot,
    ) {}

    public function handle(MessageReceived $event): void
    {
        Log::info('=== WA EVENT MASUK ===', [
            'from' => $event->from(),
            'body' => $event->body(),
            'fromMe' => $event->fromMe(),
        ]);

        if ($event->fromMe()) {
            return;
        }

        $this->bot->handle($event->from(), $event->body());
    }
}
