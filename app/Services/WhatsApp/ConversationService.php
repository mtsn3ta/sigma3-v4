<?php

namespace App\Services\WhatsApp;

use App\Models\WaConversation;

class ConversationService
{
    public function get(string $phone): WaConversation
    {
        $conv = WaConversation::firstOrCreate(
            ['phone' => $phone],
            [
                'state' => 'menu',
                'menu' => null,
                'data' => [],
            ]
        );

        // Session TTL (Time-To-Live): Jika user tidak aktif lebih dari 15 menit,
        // reset sesi percakapan kembali ke menu utama agar chat lama tidak terbawa.
        if ($conv->state !== 'menu' && $conv->updated_at && $conv->updated_at->diffInMinutes(now()) >= 15) {
            $conv->update([
                'state' => 'menu',
                'menu' => null,
                'data' => [],
            ]);
            $conv->refresh();
        }

        return $conv;
    }

    public function reset(string $phone): void
    {
        WaConversation::updateOrCreate(

            ['phone' => $phone],

            [

                'state' => 'menu',

                'menu' => null,

                'data' => [],

            ]

        );
    }

    public function update(
        string $phone,
        string $state,
        ?string $menu = null,
        array $data = [],
    ): void {

        WaConversation::updateOrCreate(

            ['phone' => $phone],

            [

                'state' => $state,

                'menu' => $menu,

                'data' => $data,

            ]

        );
    }
}
