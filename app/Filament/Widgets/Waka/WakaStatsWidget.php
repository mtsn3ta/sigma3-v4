<?php

namespace App\Filament\Widgets\Waka;

use App\Models\Ekstrakurikuler;
use App\Models\IzinKeluar;
use App\Models\KonselingSiswa;
use App\Models\KehadiranSekolah;
use App\Models\Prestasi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WakaStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('waka_kesiswaan');
    }

    protected function getStats(): array
    {
        $hadirHariIni = KehadiranSekolah::whereDate('tanggal', today())
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        $izinKeluarHariIni = IzinKeluar::whereDate('tanggal', today())->count();
        $kasusBkAktif = KonselingSiswa::whereYear('created_at', now()->year)->count();
        $prestasiTahunIni = Prestasi::whereYear('tanggal', now()->year)->count();
        $totalEkskul = Ekstrakurikuler::count();

        // =========================================================
        // Style Preset (Modern Light SaaS / Premium Clean)
        // =========================================================
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        return [
            Stat::make('Kehadiran Hari Ini', (string) $hadirHariIni)
                ->description('Akumulasi kehadiran harian siswa')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-finger-print')
                ->color('success')
                ->chart([0, 15, 30, 60, 90, $hadirHariIni ?: 10])
                ->extraAttributes($premiumStyle),

            Stat::make('Izin Keluar Hari Ini', (string) $izinKeluarHariIni)
                ->description('Siswa izin keluar area madrasah')
                ->descriptionIcon($izinKeluarHariIni > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->icon('heroicon-o-arrow-right-start-on-rectangle')
                ->color($izinKeluarHariIni > 0 ? 'warning' : 'success')
                ->chart($izinKeluarHariIni > 0 ? [0, 2, 1, 4, 3, $izinKeluarHariIni] : [0, 0, 0, 0, 0])
                ->extraAttributes($premiumStyle),

            Stat::make('Kasus BK Tahun Ini', (string) $kasusBkAktif)
                ->description('Penanganan kedisiplinan & konseling')
                ->descriptionIcon('heroicon-m-scale')
                ->icon('heroicon-o-shield-exclamation')
                ->color('danger')
                ->chart([0, 3, 2, 5, 4, $kasusBkAktif ?: 0])
                ->extraAttributes($premiumStyle),

            Stat::make('Prestasi Tahun Ini', (string) $prestasiTahunIni)
                ->description('Penghargaan diraih di tahun ' . now()->year)
                ->descriptionIcon('heroicon-m-star')
                ->icon('heroicon-o-trophy')
                ->color('info')
                ->chart([0, 2, 1, 3, 2, $prestasiTahunIni ?: 1])
                ->extraAttributes($premiumStyle),

            Stat::make('Ekstrakurikuler Aktif', (string) $totalEkskul)
                ->description('Unit pengembangan diri beroperasi')
                ->descriptionIcon('heroicon-m-fire')
                ->icon('heroicon-o-puzzle-piece')
                ->color('primary')
                ->chart([5, 5, 5, 5, 5]) // Flat line karena bukan data fluktuatif harian
                ->extraAttributes($premiumStyle),
        ];
    }
}
