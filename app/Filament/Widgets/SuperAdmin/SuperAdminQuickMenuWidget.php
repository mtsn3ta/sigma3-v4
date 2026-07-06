<?php

namespace App\Filament\Widgets\SuperAdmin;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class SuperAdminQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.super-admin.quick-menu';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;
    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('super_admin') ?? false;
    }
}
