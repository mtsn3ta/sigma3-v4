<?php

namespace App\Filament\Widgets\KepMad;

use App\Models\DaftarNilai;
use App\Models\JurnalMengajar;
use App\Models\Pegawai;
use App\Models\PerangkatPembelajaran;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class KepMadUploadStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user?->hasRole('kepala_madrasah') ?? false;
    }

    protected function getStats(): array
    {
        $dari = now('Asia/Jakarta')->startOfMonth();
        $sampai = now('Asia/Jakarta')->endOfMonth();

        $perangkat = PerangkatPembelajaran::query()
            ->whereBetween('created_at', [$dari, $sampai])
            ->count();

        $jurnal = JurnalMengajar::query()
            ->whereBetween('created_at', [$dari, $sampai])
            ->count();

        $nilai = DaftarNilai::query()
            ->whereBetween('created_at', [$dari, $sampai])
            ->count();

        $dokumentasi = JurnalMengajar::query()
            ->whereBetween('created_at', [$dari, $sampai])
            ->where(function ($query) {
                $query->whereNotNull('dokumentasi_foto')
                    ->orWhereNotNull('dokumentasi_video');
            })
            ->count();

        $guruQuery = Pegawai::query()
            ->where('jenis_pegawai', 'internal')
            ->where('status', 'aktif')
            ->whereHas('user.roles', fn ($query) => $query->where('name', 'guru'));

        $totalGuru = (clone $guruQuery)->count();

        $guruLengkap = (clone $guruQuery)
            ->whereHas('perangkatPembelajaran', fn ($query) => $query->whereBetween('created_at', [$dari, $sampai]))
            ->whereHas('jurnalMengajar', fn ($query) => $query->whereBetween('created_at', [$dari, $sampai]))
            ->count();

        $belumLengkap = max($totalGuru - $guruLengkap, 0);

        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        $alarmStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #fecaca; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);'
        ];

        return [
            Stat::make('Perangkat Pembelajaran', (string) $perangkat)
                ->description('Diunggah bulan ' . now('Asia/Jakarta')->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-document-arrow-up')
                ->icon('heroicon-o-folder-open')
                ->color('success')
                ->chart([0, 2, 4, 6, 8, $perangkat ?: 2])
                ->extraAttributes($premiumStyle),

            Stat::make('Jurnal Mengajar', (string) $jurnal)
                ->description('Entri jurnal bulan ini')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->icon('heroicon-o-book-open')
                ->color('info')
                ->chart([0, 5, 10, 15, 20, $jurnal ?: 5])
                ->extraAttributes($premiumStyle),

            Stat::make('Daftar Nilai', (string) $nilai)
                ->description('Rekap nilai yang diunggah')
                ->descriptionIcon('heroicon-m-table-cells')
                ->icon('heroicon-o-document-chart-bar')
                ->color('warning')
                ->chart([0, 1, 3, 5, 7, $nilai ?: 1])
                ->extraAttributes($premiumStyle),

            Stat::make('Dokumentasi KBM', (string) $dokumentasi)
                ->description('Jurnal dengan foto/video')
                ->descriptionIcon('heroicon-m-camera')
                ->icon('heroicon-o-photo')
                ->color('primary')
                ->chart([0, 2, 3, 6, 9, $dokumentasi ?: 2])
                ->extraAttributes($premiumStyle),

            Stat::make('Guru Belum Lengkap', (string) $belumLengkap)
                ->description($belumLengkap > 0
                    ? 'Belum unggah perangkat & jurnal bulan ini'
                    : 'Semua guru sudah melengkapi unggahan')
                ->descriptionIcon($belumLengkap > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-badge')
                ->icon($belumLengkap > 0 ? 'heroicon-o-shield-exclamation' : 'heroicon-o-check-circle')
                ->color($belumLengkap > 0 ? 'danger' : 'success')
                ->chart([0, 2, 4, 6, 8, $belumLengkap ?: 0])
                ->extraAttributes($belumLengkap > 0 ? $alarmStyle : $premiumStyle),
        ];
    }
}
