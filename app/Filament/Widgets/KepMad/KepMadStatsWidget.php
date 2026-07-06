<?php

namespace App\Filament\Widgets\KepMad;

use App\Models\Ekstrakurikuler;
use App\Models\KonselingSiswa;
use App\Models\KehadiranSekolah;
use App\Models\Prestasi;
use App\Models\Siswa;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class KepMadStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('kepala_madrasah') ?? false;
    }

    protected function getStats(): array
    {
        $totalSiswa = Siswa::aktif()->count();
        $totalGuru = Role::findByName('guru')?->users()->count() ?? 0;

        $hadirHariIni = KehadiranSekolah::whereDate('tanggal', today())
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        $prestasiTahunIni = Prestasi::whereYear('tanggal', now()->year)->count();
        $kasusBkAktif = KonselingSiswa::whereYear('created_at', now()->year)->count();
        $totalEkskul = Ekstrakurikuler::count();

        // =========================================================
        // Style Preset (Modern Light SaaS / Premium Clean)
        // =========================================================
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        return [
            Stat::make('Total Siswa', (string) $totalSiswa)
                ->description('Peserta didik berstatus aktif')
                ->descriptionIcon('heroicon-m-check-badge')
                ->icon('heroicon-o-academic-cap')
                ->color('success')
                ->chart([50, 100, 150, 200, 250, $totalSiswa ?: 50])
                ->extraAttributes($premiumStyle),

            Stat::make('Total Guru', (string) $totalGuru)
                ->description('Tenaga pendidik terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->icon('heroicon-o-briefcase')
                ->color('info')
                ->chart([10, 15, 20, 25, 30, $totalGuru ?: 10])
                ->extraAttributes($premiumStyle),

            Stat::make('Kehadiran Hari Ini', (string) $hadirHariIni)
                ->description('Akumulasi siswa hadir & terlambat')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-finger-print')
                ->color('primary')
                ->chart([0, 10, 30, 70, 100, $hadirHariIni ?: 10])
                ->extraAttributes($premiumStyle),

            Stat::make('Ekstrakurikuler Aktif', (string) $totalEkskul)
                ->description('Divisi pengembangan bakat')
                ->descriptionIcon('heroicon-m-fire')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->chart([2, 4, 6, 8, 10, $totalEkskul ?: 5])
                ->extraAttributes($premiumStyle),

            Stat::make('Prestasi Tahun Ini', (string) $prestasiTahunIni)
                ->description('Penghargaan di tahun ' . now()->year)
                ->descriptionIcon('heroicon-m-star')
                ->icon('heroicon-o-trophy')
                ->color('success')
                ->chart([0, 2, 1, 4, 3, $prestasiTahunIni ?: 1])
                ->extraAttributes($premiumStyle),

            Stat::make('Kasus BK Tahun Ini', (string) $kasusBkAktif)
                ->description('Tindak lanjut bimbingan konseling')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->icon('heroicon-o-shield-exclamation')
                ->color('danger')
                ->chart([0, 5, 2, 8, 4, $kasusBkAktif ?: 0])
                ->extraAttributes($premiumStyle),
        ];
    }
}
