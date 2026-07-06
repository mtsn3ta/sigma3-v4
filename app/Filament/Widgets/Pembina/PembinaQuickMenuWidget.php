<?php

namespace App\Filament\Widgets\Pembina;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class PembinaQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.pembina.quick-menu';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('pembina') ?? false;
    }
}
