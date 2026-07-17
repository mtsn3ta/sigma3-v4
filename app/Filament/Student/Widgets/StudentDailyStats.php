<?php

namespace App\Filament\Student\Widgets;

use App\Models\IzinKeluar;
use App\Models\KehadiranSekolah;
use App\Models\PermasalahanSiswa;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StudentDailyStats extends StatsOverviewWidget
{
    protected static ?int $sort = -9;

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $siswa = $user->siswa;

        if (! $siswa) {
            return [];
        }

        // =========================================================
        // 1. Absensi Hari Ini
        // =========================================================
        $absenHariIni = KehadiranSekolah::query()
            ->where('siswa_id', $siswa->id)
            ->whereDate('tanggal', today())
            ->first();

        $statusAbsen = $absenHariIni
            ? strtoupper($absenHariIni->status_label)
            : 'BELUM TERCATAT';

        $deskripsiAbsen = $absenHariIni
            ? ($absenHariIni->jam_datang
                ? 'Waktu masuk: ' . \Illuminate\Support\Carbon::parse($absenHariIni->jam_datang)->format('H:i WIB')
                : 'Sumber data: ' . $absenHariIni->sumber_label)
            : 'Sistem belum menerima data kehadiran';

        $absenColor = $absenHariIni?->isHadir() ? 'success' : 'danger';
        $absenIcon = $absenHariIni?->isHadir() ? 'heroicon-m-check-badge' : 'heroicon-m-exclamation-circle';

        // Efek grafik: naik jika hadir, datar/turun jika tidak
        $absenChart = $absenHariIni?->isHadir() ? [0, 10, 25, 40, 50] : [50, 40, 20, 10, 0];

        // =========================================================
        // 2. Izin Keluar Terakhir
        // =========================================================
        $izinTerakhir = IzinKeluar::query()
            ->where('siswa_id', $siswa->id)
            ->latest('tanggal')
            ->first();

        $izinValue = $izinTerakhir
            ? $izinTerakhir->tanggal->translatedFormat('d M Y')
            : 'TIDAK ADA';

        $izinDeskripsi = $izinTerakhir
            ? 'Status: ' . $izinTerakhir->status_label
            : 'Tidak ada riwayat izin keluar';

        $izinColor = $izinTerakhir ? 'info' : 'gray';

        // =========================================================
        // 3. Total Poin BK
        // =========================================================
        $totalPoin = (int) PermasalahanSiswa::query()
            ->where('siswa_id', $siswa->id)
            ->sum('poin');

        $poinColor = $totalPoin > 0 ? 'danger' : 'success';
        $poinIcon = $totalPoin > 0 ? 'heroicon-m-shield-exclamation' : 'heroicon-m-shield-check';
        $poinDeskripsi = $totalPoin > 0 ? 'Perlu perhatian khusus' : 'Siswa memiliki rekam jejak bersih';

        // Efek grafik: tajam jika ada poin (bahaya), datar jika 0 (aman)
        $poinChart = $totalPoin > 0 ? [0, 20, 10, 40, 20, 50] : [0, 0, 0, 0, 0];

        // =========================================================
        // Custom Style Injector
        // =========================================================
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e5e7eb; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02); background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);'
        ];

        return [
            Stat::make('Kehadiran Hari Ini', $statusAbsen)
                ->description($deskripsiAbsen)
                ->descriptionIcon($absenIcon)
                ->icon('heroicon-o-finger-print')
                ->color($absenColor)
                ->chart($absenChart)
                ->extraAttributes($premiumStyle),

            Stat::make('Izin Keluar Terakhir', $izinValue)
                ->description($izinDeskripsi)
                ->descriptionIcon($izinTerakhir ? 'heroicon-m-arrow-right-on-rectangle' : 'heroicon-m-information-circle')
                ->icon('heroicon-o-document-text')
                ->color($izinColor)
                ->chart([10, 20, 15, 30, 25]) // Grafik statis untuk estetika
                ->extraAttributes($premiumStyle),

            Stat::make('Total Poin Pelanggaran', $totalPoin . ' Poin')
                ->description($poinDeskripsi)
                ->descriptionIcon($poinIcon)
                ->icon('heroicon-o-scale')
                ->color($poinColor)
                ->chart($poinChart)
                ->extraAttributes($premiumStyle),
        ];
    }
}
