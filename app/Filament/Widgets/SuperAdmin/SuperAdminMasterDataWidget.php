<?php

namespace App\Filament\Widgets\SuperAdmin;

use App\Models\Ekstrakurikuler;
use App\Models\Kelas;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class SuperAdminMasterDataWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // Hitung pegawai berdasarkan role Spatie di tabel users
        $totalGuru = Role::findByName('guru')
            ?->users()->count() ?? 0;

        $totalPembina = Role::findByName('pembina')
            ?->users()->count() ?? 0;

        $totalPelatih = Role::findByName('pelatih')
            ?->users()->count() ?? 0;

        // =========================================================
        // Style Preset (Modern Light SaaS / Premium Clean)
        // =========================================================
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        return [
            Stat::make('Total Kelas', $this->formatNumber(Kelas::count()))
                ->description('Unit kelas terdaftar di madrasah')
                ->descriptionIcon('heroicon-m-rectangle-group')
                ->icon('heroicon-o-academic-cap')
                ->color('primary')
                ->chart([5, 10, 8, 15, 12, Kelas::count() ?: 5])
                ->extraAttributes($premiumStyle),

            Stat::make('Total Ekstrakurikuler', $this->formatNumber(Ekstrakurikuler::count()))
                ->description('Divisi ekskul aktif berjalan')
                ->descriptionIcon('heroicon-m-fire')
                ->icon('heroicon-o-trophy')
                ->color('info')
                ->chart([2, 5, 4, 8, 10, Ekstrakurikuler::count() ?: 5])
                ->extraAttributes($premiumStyle),

            Stat::make('Total Guru', $this->formatNumber($totalGuru))
                ->description('Tenaga pendidik aktif dengan akun')
                ->descriptionIcon('heroicon-m-user-group')
                ->icon('heroicon-o-briefcase')
                ->color('success')
                ->chart([10, 20, 15, 30, 25, $totalGuru ?: 5])
                ->extraAttributes($premiumStyle),

            Stat::make('Total Pembina', $this->formatNumber($totalPembina))
                ->description('Staf penanggung jawab ekskul')
                ->descriptionIcon('heroicon-m-shield-check')
                ->icon('heroicon-o-identification')
                ->color('warning')
                ->chart([1, 4, 3, 6, 8, $totalPembina ?: 5])
                ->extraAttributes($premiumStyle),

            Stat::make('Total Pelatih', $this->formatNumber($totalPelatih))
                ->description('Instruktur teknis lapangan ekskul')
                ->descriptionIcon('heroicon-m-sparkles')
                ->icon('heroicon-o-user')
                ->color('gray')
                ->chart([2, 3, 5, 4, 7, $totalPelatih ?: 5])
                ->extraAttributes($premiumStyle),
        ];
    }

    /**
     * Helper untuk menambahkan satuan string agar layout data lebih dinamis
     */
    private function formatNumber(int $value): string
    {
        return (string) $value;
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('super_admin') ?? false;
    }
}
