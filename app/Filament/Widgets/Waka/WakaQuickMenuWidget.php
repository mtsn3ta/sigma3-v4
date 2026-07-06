<?php

namespace App\Filament\Widgets\Waka;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WakaQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.waka.quick-menu';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('waka_kesiswaan') ?? false;
    }
}
