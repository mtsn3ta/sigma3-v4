<?php

namespace App\Filament\Pages;

use App\Support\ActiveRole;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class MonitoringUploadGuru extends Page
{
    protected string $view = 'filament.pages.monitoring-upload-guru';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCloudArrowUp;

    protected static ?string $navigationLabel = 'Monitoring Upload Guru';

    protected static ?string $title = 'Monitoring Upload Guru';

    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';

    protected static ?int $navigationSort = 6;

    /**
     * Tab aktif: 'rekap' | 'jurnal'
     */
    public string $tab = 'rekap';

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return ($user?->hasRole(['kepala_madrasah', 'waka_kurikulum']) ?? false)
            || ActiveRole::is('super_admin');
    }

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['rekap', 'jurnal'], true) ? $tab : 'rekap';
    }
}
