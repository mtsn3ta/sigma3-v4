<?php

namespace App\Filament\Widgets\Pelatih;

use App\Models\AnggotaEkskul;
use App\Models\EkstrakurikulerPengampu;
use App\Models\KehadiranEkskul;
use App\Models\Prestasi;
use App\Models\SesiKegiatan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PelatihStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('pelatih');
    }

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;

        $ekskulIds = EkstrakurikulerPengampu::where('pegawai_id', $pegawaiId)
            ->where('role', 'pelatih')
            ->pluck('ekstrakurikuler_id');

        // Perhitungan data
        $totalAtlet = AnggotaEkskul::whereIn('ekstrakurikuler_id', $ekskulIds)->where('status', 'aktif')->count();
        $sesiBulanIni = SesiKegiatan::whereIn('ekstrakurikuler_id', $ekskulIds)->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $hadirBulanIni = KehadiranEkskul::whereHas('sesiKegiatan', fn($q) => $q->whereIn('ekstrakurikuler_id', $ekskulIds)->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year))->where('status', 'hadir')->count();
        $prestasiTahunIni = Prestasi::whereIn('ekstrakurikuler_id', $ekskulIds)->whereYear('tanggal', now()->year)->count();

        // Style Preset Premium yang Konsisten
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        return [
            Stat::make('Atlet Aktif', (string) $totalAtlet)
                ->description('Total siswa dalam binaan')
                ->descriptionIcon('heroicon-m-user-group')
                ->icon('heroicon-o-users')
                ->color('success')
                ->chart([0, 10, 20, 35, $totalAtlet])
                ->extraAttributes($premiumStyle),

            Stat::make('Sesi Latihan', (string) $sesiBulanIni)
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->icon('heroicon-o-clock')
                ->color('info')
                ->chart([0, 2, 1, 4, $sesiBulanIni])
                ->extraAttributes($premiumStyle),

            Stat::make('Kehadiran', (string) $hadirBulanIni)
                ->description('Kehadiran atlet bulan ini')
                ->descriptionIcon('heroicon-m-check-badge')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('primary')
                ->chart([0, 20, 40, 60, $hadirBulanIni])
                ->extraAttributes($premiumStyle),

            Stat::make('Prestasi', (string) $prestasiTahunIni)
                ->description('Total gelar tahun ' . now()->year)
                ->descriptionIcon('heroicon-m-trophy')
                ->icon('heroicon-o-trophy')
                ->color('warning')
                ->chart([0, 1, 0, 2, $prestasiTahunIni])
                ->extraAttributes($premiumStyle),
        ];
    }
}
