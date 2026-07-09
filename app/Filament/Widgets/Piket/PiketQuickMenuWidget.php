<?php

namespace App\Filament\Widgets\Piket;

use Filament\Widgets\Widget;

class PiketQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.piket.quick-menu';
    protected int|string|array $columnSpan = 1;
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('piket');
    }
}
