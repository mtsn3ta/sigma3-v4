<?php

namespace App\Filament\Widgets\Guru;

use App\Models\JurnalMengajar;
use App\Models\TugasGuruIzin;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class GuruStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('guru') ?? false;
    }

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;

        $jurnalHariIni = JurnalMengajar::where('pegawai_id', $pegawaiId)
            ->whereDate('tanggal', today())
            ->count();

        $jurnalBulanIni = JurnalMengajar::where('pegawai_id', $pegawaiId)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $tugasBelumSelesai = TugasGuruIzin::where('pegawai_id', $pegawaiId)
            ->whereIn('status', ['baru', 'disampaikan'])
            ->count();

        $tugasBulanIni = TugasGuruIzin::where('pegawai_id', $pegawaiId)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Style Preset Premium
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        return [
            Stat::make('Jurnal Hari Ini', (string) $jurnalHariIni)
                ->description($jurnalHariIni > 0 ? 'Sesi mengajar sudah dicatat' : 'Belum ada jurnal hari ini')
                ->descriptionIcon($jurnalHariIni > 0 ? 'heroicon-m-check-badge' : 'heroicon-m-pencil')
                ->icon('heroicon-o-book-open')
                ->color($jurnalHariIni > 0 ? 'success' : 'gray')
                ->chart([0, 1, 2, 3, 4, $jurnalHariIni ?: 0])
                ->extraAttributes($premiumStyle),

            Stat::make('Jurnal Bulan Ini', (string) $jurnalBulanIni)
                ->description('Total sesi mengajar di ' . now()->translatedFormat('F'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->icon('heroicon-o-document-chart-bar')
                ->color('info')
                ->chart([5, 10, 15, 20, 25, $jurnalBulanIni ?: 5])
                ->extraAttributes($premiumStyle),

            Stat::make('Tugas Inval Menunggu', (string) $tugasBelumSelesai)
                ->description($tugasBelumSelesai > 0 ? 'Segera tindak lanjuti tugas' : 'Semua tugas telah selesai')
                ->descriptionIcon($tugasBelumSelesai > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->icon('heroicon-o-clipboard-document-list')
                ->color($tugasBelumSelesai > 0 ? 'warning' : 'success')
                ->chart($tugasBelumSelesai > 0 ? [0, 1, 0, 2, 1, $tugasBelumSelesai] : [0, 0, 0, 0, 0])
                ->extraAttributes($premiumStyle),

            Stat::make('Tugas Bulan Ini', (string) $tugasBulanIni)
                ->description('Total penugasan di ' . now()->translatedFormat('F'))
                ->descriptionIcon('heroicon-m-clipboard-document')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('primary')
                ->chart([2, 3, 5, 4, 6, $tugasBulanIni ?: 2])
                ->extraAttributes($premiumStyle),
        ];
    }
}
