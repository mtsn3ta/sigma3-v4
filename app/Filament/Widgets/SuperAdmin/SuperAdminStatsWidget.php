<?php

namespace App\Filament\Widgets\SuperAdmin;

use App\Models\Pegawai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class SuperAdminStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tahunAktif = TahunAjaran::where('aktif', true)->first();

        // =========================================================
        // Style Preset (Modern Light SaaS / Premium Clean)
        // =========================================================
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        return [
            Stat::make('Total Pengguna', (string) User::count())
                ->description('Seluruh akun sistem terdaftar')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-users')
                ->color('primary')
                ->chart([10, 15, 25, 30, 45, User::count() ?: 10])
                ->extraAttributes($premiumStyle),

            Stat::make('Total Pegawai', (string) Pegawai::count())
                ->description('Staf internal dan eksternal')
                ->descriptionIcon('heroicon-m-user-plus')
                ->icon('heroicon-o-identification')
                ->color('info')
                ->chart([5, 8, 12, 14, 18, Pegawai::count() ?: 5])
                ->extraAttributes($premiumStyle),

            Stat::make('Total Siswa', (string) Siswa::aktif()->count())
                ->description('Peserta didik berstatus aktif')
                ->descriptionIcon('heroicon-m-check-badge')
                ->icon('heroicon-o-academic-cap')
                ->color('success')
                ->chart([50, 80, 120, 150, 200, Siswa::aktif()->count() ?: 50])
                ->extraAttributes($premiumStyle),

            Stat::make('Total Hak Akses (Role)', (string) Role::count())
                ->description('Tingkatan level otorisasi')
                ->descriptionIcon('heroicon-m-key')
                ->icon('heroicon-o-shield-check')
                ->color('warning')
                ->chart([2, 3, 3, 4, 4, Role::count() ?: 2])
                ->extraAttributes($premiumStyle),

            Stat::make('Total Izin (Permission)', (string) Permission::count())
                ->description('Akses keamanan modul spesifik')
                ->descriptionIcon('heroicon-m-finger-print')
                ->icon('heroicon-o-lock-closed')
                ->color('gray')
                ->chart([10, 20, 35, 40, 50, Permission::count() ?: 10])
                ->extraAttributes($premiumStyle),

            Stat::make('Tahun Ajaran Aktif', $tahunAktif?->tahun ?? 'Belum Diatur')
                ->description('Periode akademik berjalan')
                ->descriptionIcon('heroicon-m-sparkles')
                ->icon('heroicon-o-calendar-days')
                ->color($tahunAktif ? 'success' : 'danger')
                ->chart([5, 5, 5, 5, 5]) // Flat line untuk data non-numerik
                ->extraAttributes($premiumStyle),
        ];
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('super_admin') ?? false;
    }
}
