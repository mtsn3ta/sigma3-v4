<?php

namespace App\Filament\Widgets\Piket;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class PiketQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.piket.quick-menu';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('piket') ?? false;
    }
}
