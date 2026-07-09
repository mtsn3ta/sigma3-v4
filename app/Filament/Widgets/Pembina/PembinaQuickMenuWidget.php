<?php

namespace App\Filament\Widgets\Pembina;

use Filament\Widgets\Widget;

class PembinaQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.pembina.quick-menu';
    protected int|string|array $columnSpan = 1;
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('pembina');
    }
}
