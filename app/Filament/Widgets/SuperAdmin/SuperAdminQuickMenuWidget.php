<?php

namespace App\Filament\Widgets\SuperAdmin;

use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use App\Filament\Resources\Kelas\KelasResource;
use App\Filament\Resources\Pegawais\PegawaiResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Settings\SettingResource;
use App\Filament\Resources\Siswas\SiswaResource;
use App\Filament\Resources\TahunAjarans\TahunAjaranResource;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class SuperAdminQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.super-admin.quick-menu';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user?->hasRole('super_admin') ?? false;
    }

    protected function getViewData(): array
    {
        return [
            'title' => 'Akses Cepat',
            'headerIcon' => 'heroicon-o-squares-2x2',
            'theme' => 'indigo',
            'items' => [
                ['label' => 'User', 'icon' => 'heroicon-o-users', 'url' => PegawaiResource::getUrl()],
                ['label' => 'Role', 'icon' => 'heroicon-o-shield-check', 'url' => RoleResource::getUrl()],
                ['label' => 'Settings', 'icon' => 'heroicon-o-cog-6-tooth', 'url' => SettingResource::getUrl()],
                ['label' => 'Tahun Ajaran', 'icon' => 'heroicon-o-calendar', 'url' => TahunAjaranResource::getUrl()],
                ['label' => 'Kelas', 'icon' => 'heroicon-o-academic-cap', 'url' => KelasResource::getUrl()],
                ['label' => 'Pegawai', 'icon' => 'heroicon-o-briefcase', 'url' => PegawaiResource::getUrl()],
                ['label' => 'Siswa', 'icon' => 'heroicon-o-user-group', 'url' => SiswaResource::getUrl()],
                ['label' => 'Activity Log', 'icon' => 'heroicon-o-clipboard-document-list', 'url' => ActivityLogResource::getUrl()],
            ],
        ];
    }
}
