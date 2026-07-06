<?php

namespace App\Filament\Widgets\Pembina;

use App\Models\AnggotaEkskul;
use App\Models\EkstrakurikulerPengampu;
use App\Models\ProposalKegiatan;
use App\Models\LaporanBulanan;
use App\Models\SesiKegiatan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PembinaStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('pembina') ?? false;
    }

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;

        $ekskulIds = EkstrakurikulerPengampu::where('pegawai_id', $pegawaiId)
            ->where('role', 'pembina')
            ->pluck('ekstrakurikuler_id');

        $jumlahEkskul = $ekskulIds->count();
        $jumlahAnggota = AnggotaEkskul::whereIn('ekstrakurikuler_id', $ekskulIds)->where('status', 'aktif')->count();
        $sesibulanIni = SesiKegiatan::whereIn('ekstrakurikuler_id', $ekskulIds)->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();

        $proposalMenunggu = ProposalKegiatan::whereIn('ekstrakurikuler_id', $ekskulIds)->where('status', 'pending_pembina')->count();
        $laporanMenunggu = LaporanBulanan::whereIn('ekstrakurikuler_id', $ekskulIds)->where('status', 'pending_pembina')->count();

        // Style Preset Premium yang Konsisten
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        return [
            Stat::make('Ekskul Diampu', (string) $jumlahEkskul)
                ->description('Ekstrakurikuler di bawah bimbingan Anda')
                ->descriptionIcon('heroicon-m-shield-check')
                ->icon('heroicon-o-building-library')
                ->color('primary')
                ->chart([$jumlahEkskul, $jumlahEkskul, $jumlahEkskul]) // Flat chart for fixed data
                ->extraAttributes($premiumStyle),

            Stat::make('Total Anggota', (string) $jumlahAnggota)
                ->description('Siswa terdaftar & aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->icon('heroicon-o-users')
                ->color('success')
                ->chart([0, 10, 25, 45, $jumlahAnggota])
                ->extraAttributes($premiumStyle),

            Stat::make('Sesi Bulan Ini', (string) $sesibulanIni)
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->icon('heroicon-o-document-chart-bar')
                ->color('info')
                ->chart([0, 1, 3, 2, $sesibulanIni])
                ->extraAttributes($premiumStyle),

            Stat::make('Proposal Menunggu', (string) $proposalMenunggu)
                ->description($proposalMenunggu > 0 ? 'Tindakan diperlukan!' : 'Semua proposal sudah diproses')
                ->descriptionIcon($proposalMenunggu > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->icon('heroicon-o-document-plus')
                ->color($proposalMenunggu > 0 ? 'danger' : 'success')
                ->url($proposalMenunggu > 0 ? '/admin/proposal-kegiatans' : null)
                ->chart($proposalMenunggu > 0 ? [0, 2, 1, $proposalMenunggu] : [0, 0, 0, 0])
                ->extraAttributes($premiumStyle),

            Stat::make('Laporan Menunggu', (string) $laporanMenunggu)
                ->description($laporanMenunggu > 0 ? 'Perlu review laporan' : 'Semua laporan aman')
                ->descriptionIcon($laporanMenunggu > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->icon('heroicon-o-clipboard-document-check')
                ->color($laporanMenunggu > 0 ? 'danger' : 'success')
                ->url($laporanMenunggu > 0 ? '/admin/laporan-bulanans' : null)
                ->chart($laporanMenunggu > 0 ? [0, 2, 1, $laporanMenunggu] : [0, 0, 0, 0])
                ->extraAttributes($premiumStyle),
        ];
    }
}
