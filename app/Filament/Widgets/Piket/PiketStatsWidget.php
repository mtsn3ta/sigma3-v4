<?php

namespace App\Filament\Widgets\Piket;

use App\Models\IzinKeluar;
use App\Models\KehadiranSekolah;
use App\Models\TugasGuruIzin;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PiketStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('piket');
    }

    protected function getStats(): array
    {
        $hadirHariIni = KehadiranSekolah::whereDate('tanggal', today())
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        $terlambatHariIni = KehadiranSekolah::whereDate('tanggal', today())
            ->where('status', 'terlambat')
            ->count();

        $alfaHariIni = KehadiranSekolah::whereDate('tanggal', today())
            ->where('status', 'alfa')
            ->count();

        $izinKeluarHariIni = IzinKeluar::whereDate('tanggal', today())->count();
        $izinMasihKeluar = IzinKeluar::whereDate('tanggal', today())
            ->where('status_proses', 'keluar')
            ->count();

        $tugasGuruHariIni = TugasGuruIzin::whereDate('tanggal', today())->count();

        // Style Preset Premium
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        return [
            Stat::make('Hadir & Terlambat', (string) $hadirHariIni)
                ->description('Total siswa terpantau hadir')
                ->descriptionIcon('heroicon-m-check-badge')
                ->icon('heroicon-o-user-group')
                ->color('success')
                ->chart([0, 20, 50, 100, 150, $hadirHariIni ?: 0])
                ->extraAttributes($premiumStyle),

            Stat::make('Terlambat', (string) $terlambatHariIni)
                ->description($terlambatHariIni > 0 ? 'Segera input data keterlambatan' : 'Siswa datang tepat waktu')
                ->descriptionIcon($terlambatHariIni > 0 ? 'heroicon-m-clock' : 'heroicon-m-check-circle')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($terlambatHariIni > 0 ? 'warning' : 'success')
                ->chart($terlambatHariIni > 0 ? [0, 1, 3, 2, 5, $terlambatHariIni] : [0, 0, 0, 0, 0])
                ->extraAttributes($premiumStyle),

            Stat::make('Alfa (Tanpa Keterangan)', (string) $alfaHariIni)
                ->description($alfaHariIni > 0 ? 'Perlu tindak lanjut segera' : 'Semua siswa terpantau')
                ->descriptionIcon($alfaHariIni > 0 ? 'heroicon-m-x-circle' : 'heroicon-m-check-circle')
                ->icon('heroicon-o-user-minus')
                ->color($alfaHariIni > 0 ? 'danger' : 'success')
                ->chart($alfaHariIni > 0 ? [0, 1, 2, 1, 4, $alfaHariIni] : [0, 0, 0, 0, 0])
                ->extraAttributes($premiumStyle),

            Stat::make('Siswa Izin Keluar', (string) $izinKeluarHariIni)
                ->description($izinMasihKeluar . ' siswa masih di luar sekolah')
                ->descriptionIcon('heroicon-m-arrow-right-start-on-rectangle')
                ->icon('heroicon-o-map-pin')
                ->color($izinMasihKeluar > 0 ? 'warning' : 'info')
                ->chart([0, 1, 2, 3, 4, $izinKeluarHariIni ?: 0])
                ->extraAttributes($premiumStyle),

            Stat::make('Tugas Guru Izin (Inval)', (string) $tugasGuruHariIni)
                ->description($tugasGuruHariIni > 0 ? 'Jadwal guru berhalangan' : 'Jadwal mengajar normal')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->icon('heroicon-o-clipboard-document-list')
                ->color($tugasGuruHariIni > 0 ? 'info' : 'gray')
                ->chart($tugasGuruHariIni > 0 ? [0, 1, 2, 3, $tugasGuruHariIni] : [0, 0, 0, 0, 0])
                ->extraAttributes($premiumStyle),
        ];
    }
}
