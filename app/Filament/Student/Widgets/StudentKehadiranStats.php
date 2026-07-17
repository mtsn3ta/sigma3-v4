<?php

namespace App\Filament\Student\Widgets;

use App\Models\KehadiranIbadah;
use App\Models\KehadiranSekolah;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StudentKehadiranStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $siswa = $user->siswa;

        if (! $siswa) {
            return [];
        }

        // ── 1. Kehadiran Sekolah ──────────────────────────────────────
        $totalSekolah = KehadiranSekolah::where('siswa_id', $siswa->id)->count();
        $hadirSekolah = KehadiranSekolah::where('siswa_id', $siswa->id)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();
        $persenSekolah = $totalSekolah > 0
            ? round(($hadirSekolah / $totalSekolah) * 100)
            : 0;

        // ── 2. Kehadiran Ibadah Dhuha ────────────────────────────────
        $totalDhuha = KehadiranIbadah::where('siswa_id', $siswa->id)
            ->where('jenis', 'dhuha')
            ->count();
        $hadirDhuha = KehadiranIbadah::where('siswa_id', $siswa->id)
            ->where('jenis', 'dhuha')
            ->whereIn('status', ['hadir', 'menstruasi'])
            ->count();
        $persenDhuha = $totalDhuha > 0
            ? round(($hadirDhuha / $totalDhuha) * 100)
            : 0;

        // ── 3. Kehadiran Ibadah Dzuhur ───────────────────────────────
        $totalDzuhur = KehadiranIbadah::where('siswa_id', $siswa->id)
            ->where('jenis', 'dzuhur')
            ->count();
        $hadirDzuhur = KehadiranIbadah::where('siswa_id', $siswa->id)
            ->where('jenis', 'dzuhur')
            ->whereIn('status', ['hadir', 'menstruasi'])
            ->count();
        $persenDzuhur = $totalDzuhur > 0
            ? round(($hadirDzuhur / $totalDzuhur) * 100)
            : 0;

        // ── 4. Ekskul yang diikuti ───────────────────────────────────
        $ekskulAktif = $siswa->anggotaEkskul()
            ->with('ekstrakurikuler')
            ->whereHas('tahunAjaran', fn($q) => $q->where('aktif', true))
            ->where('status', 'aktif')
            ->get()
            ->map(fn($a) => $a->ekstrakurikuler?->nama)
            ->filter()
            ->implode(', ');

        // =========================================================
        // Style Preset (Modern Light SaaS / Futuristic Clean)
        // =========================================================
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        // Logika Grafik (Trend naik jika >= 75%, Trend turun jika di bawahnya)
        $chartSekolah = $persenSekolah >= 75 ? [10, 25, 40, 60, 80, 100] : [100, 80, 50, 30, 10];
        $chartDhuha   = $persenDhuha >= 75   ? [10, 25, 40, 60, 80, 100] : [100, 80, 50, 30, 10];
        $chartDzuhur  = $persenDzuhur >= 75  ? [10, 25, 40, 60, 80, 100] : [100, 80, 50, 30, 10];

        return [
            Stat::make('Kehadiran Sekolah', $persenSekolah . '%')
                ->description($hadirSekolah . ' dari ' . $totalSekolah . ' hari masuk')
                ->descriptionIcon($persenSekolah >= 75 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->icon('heroicon-o-academic-cap')
                ->color($persenSekolah >= 75 ? 'success' : 'danger')
                ->chart($chartSekolah)
                ->extraAttributes($premiumStyle),

            Stat::make('Ibadah Dhuha', $persenDhuha . '%')
                ->description($hadirDhuha . ' dari ' . $totalDhuha . ' sesi terdata')
                ->descriptionIcon('heroicon-m-sun')
                ->icon('heroicon-o-sparkles')
                ->color($persenDhuha >= 75 ? 'success' : 'warning')
                ->chart($chartDhuha)
                ->extraAttributes($premiumStyle),

            Stat::make('Ibadah Dzuhur', $persenDzuhur . '%')
                ->description($hadirDzuhur . ' dari ' . $totalDzuhur . ' sesi terdata')
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-heart')
                ->color($persenDzuhur >= 75 ? 'success' : 'warning')
                ->chart($chartDzuhur)
                ->extraAttributes($premiumStyle),

            Stat::make('Ekskul Saya', $ekskulAktif ?: 'Belum Terdaftar')
                ->description('Tahun Ajaran Aktif')
                ->descriptionIcon('heroicon-m-check-badge')
                ->icon('heroicon-o-user-group')
                ->color('info')
                ->chart([20, 20, 20, 20, 20]) // Flat chart untuk data teks
                ->extraAttributes($premiumStyle),

            Stat::make('Total Hadir Sekolah', (string) $hadirSekolah . ' Hari')
                ->description('Total akumulasi kehadiran')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->icon('heroicon-o-chart-bar')
                ->color('primary')
                ->chart([5, 10, 15, 25, 40, 60, $hadirSekolah])
                ->extraAttributes($premiumStyle),
        ];
    }
}
