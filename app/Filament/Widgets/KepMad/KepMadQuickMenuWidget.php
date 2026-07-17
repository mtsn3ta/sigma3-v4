<?php

namespace App\Filament\Widgets\KepMad;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class KepMadQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.kep-mad.quick-menu';
    protected int|string|array $columnSpan = 1;
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('kepala_madrasah') ?? false;
    }
}
