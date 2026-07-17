<?php

namespace App\Filament\Widgets\Pelatih;

use Filament\Widgets\Widget;

class PelatihQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.pelatih.quick-menu';
    protected int|string|array $columnSpan = 1;
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('pelatih');
    }
}
