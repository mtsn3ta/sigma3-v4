<?php

namespace App\Filament\Widgets\KepMad;

use App\Models\LaporanBulanan;
use App\Models\LaporanKegiatan;
use App\Models\ProposalKegiatan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class KepMadApprovalWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('kepala_madrasah') ?? false;
    }

    protected function getStats(): array
    {
        $proposalMenunggu = ProposalKegiatan::where('status', 'pending_kepala')->count();
        $laporanKegiatanMenunggu = LaporanKegiatan::where('status', 'pending_kepala')->count();
        $laporanBulananMenunggu = LaporanBulanan::where('status', 'pending_kepala')->count();

        // =========================================================
        // Style Preset (Modern Light SaaS / Premium Clean)
        // =========================================================
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); transition: transform 0.2s ease-in-out;'
        ];

        return [
            Stat::make('Proposal Menunggu', (string) $proposalMenunggu)
                ->description($proposalMenunggu > 0 ? 'Menunggu pengesahan Kepala Madrasah' : 'Semua proposal telah diproses')
                ->descriptionIcon($proposalMenunggu > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->icon('heroicon-o-document-text')
                ->color($proposalMenunggu > 0 ? 'danger' : 'success')
                ->chart($proposalMenunggu > 0 ? [0, 2, 5, 3, 8, $proposalMenunggu] : [0, 0, 0, 0, 0])
                ->url('/admin/proposal-kegiatans')
                ->extraAttributes($premiumStyle),

            Stat::make('Laporan Kegiatan', (string) $laporanKegiatanMenunggu)
                ->description($laporanKegiatanMenunggu > 0 ? 'Menunggu validasi pelaksanaan' : 'Semua laporan telah divalidasi')
                ->descriptionIcon($laporanKegiatanMenunggu > 0 ? 'heroicon-m-clock' : 'heroicon-m-check-circle')
                ->icon('heroicon-o-clipboard-document-check')
                ->color($laporanKegiatanMenunggu > 0 ? 'danger' : 'success')
                ->chart($laporanKegiatanMenunggu > 0 ? [0, 1, 4, 2, 6, $laporanKegiatanMenunggu] : [0, 0, 0, 0, 0])
                ->url('/admin/laporan-kegiatans')
                ->extraAttributes($premiumStyle),

            Stat::make('Laporan Bulanan', (string) $laporanBulananMenunggu)
                ->description($laporanBulananMenunggu > 0 ? 'Tinjauan laporan periodik tertunda' : 'Laporan bulanan terverifikasi')
                ->descriptionIcon($laporanBulananMenunggu > 0 ? 'heroicon-m-bell-alert' : 'heroicon-m-check-circle')
                ->icon('heroicon-o-calendar-days')
                ->color($laporanBulananMenunggu > 0 ? 'danger' : 'success')
                ->chart($laporanBulananMenunggu > 0 ? [0, 3, 1, 5, 4, $laporanBulananMenunggu] : [0, 0, 0, 0, 0])
                ->url('/admin/laporan-bulanans')
                ->extraAttributes($premiumStyle),
        ];
    }
}
