<?php

namespace App\Services\WhatsApp;

use App\Models\WaConversation;

class ConversationService
{
    public function get(string $phone): WaConversation
    {
        return WaConversation::firstOrCreate(

            ['phone' => $phone],

            [

                'state' => 'menu',

                'menu' => null,

                'data' => [],

            ]

        );
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
