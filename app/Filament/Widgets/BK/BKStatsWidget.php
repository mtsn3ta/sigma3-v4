<?php

namespace App\Filament\Widgets\BK;

use App\Models\IzinKeluar;
use App\Models\KonselingSiswa;
use App\Models\PermasalahanSiswa;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class BKStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('bk') ?? false;
    }

    protected function getStats(): array
    {
        $siswaBermasalah = PermasalahanSiswa::distinct('siswa_id')->count('siswa_id');
        $konselingHariIni = KonselingSiswa::whereDate('tanggal', today())->count();
        $izinKeluarHariIni = IzinKeluar::whereDate('tanggal', today())->count();

        $kasusBelumSelesai = PermasalahanSiswa::where(function ($query) {
            $query->whereNull('penyelesaian')
                ->orWhere('penyelesaian', '');
        })->count();

        // =========================================================
        // Style Preset (Modern Light SaaS / Premium Clean)
        // =========================================================
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); transition: transform 0.2s ease-in-out;'
        ];

        return [
            Stat::make('Siswa Pendampingan', (string) $siswaBermasalah)
                ->description('Total siswa dengan catatan khusus')
                ->descriptionIcon('heroicon-m-users')
                ->icon('heroicon-o-identification')
                ->color('danger')
                ->chart([0, 5, 2, 8, 4, $siswaBermasalah ?: 1])
                ->extraAttributes($premiumStyle),

            Stat::make('Konseling Hari Ini', (string) $konselingHariIni)
                ->description('Jadwal sesi bimbingan hari ini')
                ->descriptionIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('warning')
                ->chart([0, 2, 4, 3, 5, $konselingHariIni ?: 1])
                ->extraAttributes($premiumStyle),

            Stat::make('Izin Keluar Terpantau', (string) $izinKeluarHariIni)
                ->description('Mobilitas siswa keluar area madrasah')
                ->descriptionIcon('heroicon-m-arrow-right-start-on-rectangle')
                ->icon('heroicon-o-map-pin')
                ->color('info')
                ->chart([0, 1, 3, 2, 4, $izinKeluarHariIni ?: 0])
                ->extraAttributes($premiumStyle),

            Stat::make('Tindak Lanjut Tertunda', (string) $kasusBelumSelesai)
                ->description($kasusBelumSelesai > 0 ? 'Perlu segera diselesaikan' : 'Semua kasus telah tertangani')
                ->descriptionIcon($kasusBelumSelesai > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-badge')
                ->icon('heroicon-o-folder-open')
                ->color($kasusBelumSelesai > 0 ? 'danger' : 'success')
                ->chart($kasusBelumSelesai > 0 ? [0, 3, 6, 4, 8, $kasusBelumSelesai] : [0, 0, 0, 0, 0])
                ->extraAttributes($premiumStyle),
        ];
    }
}
