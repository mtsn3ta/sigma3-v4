<?php

namespace App\Filament\Widgets\Waka;

use Filament\Widgets\Widget;

class WakaQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.waka.quick-menu';
    protected int|string|array $columnSpan = 1;
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('waka_kesiswaan');
    }
}
