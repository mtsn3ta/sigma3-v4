<?php

namespace App\Filament\Widgets\Pelatih;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class PelatihQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.pelatih.quick-menu';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('pelatih') ?? false;
    }
}
