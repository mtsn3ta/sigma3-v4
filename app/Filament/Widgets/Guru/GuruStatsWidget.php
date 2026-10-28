<?php

namespace App\Filament\Widgets\Guru;

use App\Models\JurnalMengajar;
use App\Models\TugasGuruIzin;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use App\Models\AgendaMadrasah; // BARU: Impor Model Agenda
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use App\Support\JamPelajaranHelper;

class GuruStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('guru');
    }

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;
        $pegawaiId = $user->pegawai?->id;

        // 1. Hitung Jurnal Mengajar
        $jurnalHariIni = JurnalMengajar::where('pegawai_id', $pegawaiId)
            ->whereDate('tanggal', today('Asia/Jakarta'))
            ->count();

        $jurnalBulanIni = JurnalMengajar::where('pegawai_id', $pegawaiId)
            ->whereMonth('tanggal', now('Asia/Jakarta')->month)
            ->whereYear('tanggal', now('Asia/Jakarta')->year)
            ->count();

        // 2. Hitung Tugas Izin
        $tugasBelumSelesai = TugasGuruIzin::where('pegawai_id', $pegawaiId)
            ->whereIn('status', ['baru', 'disampaikan'])
            ->count();

        // 3. LOGIKA CEGATAN AGENDA MADRASAH UNTUK STATISTIK JURNAL
        $alpaHariIni = 0;
        $hariPenuhAgenda = false;
        $pesanAgendaPusat = '';

        if (class_exists(AgendaMadrasah::class)) {
            // LAPIS 1 & 2: Cek Hari Minggu atau Agenda Pusat (Libur Nasional / Kegiatan Bersama)
            if (today('Asia/Jakarta')->isSunday()) {
                $hariPenuhAgenda = true;
                $pesanAgendaPusat = 'Libur Akhir Pekan';
            } else {
                $agendaPusat = AgendaMadrasah::aktifPada(today('Asia/Jakarta'))
                    ->whereIn('tipe_agenda', ['libur_nasional', 'kegiatan_bersama'])
                    ->first();

                if ($agendaPusat) {
                    $hariPenuhAgenda = true;
                    $pesanAgendaPusat = $agendaPusat->nama_agenda;
                }
            }
        }

        // Jika hari ini libur penuh sekolah, otomatis alpa/kurang jam adalah 0
        if ($hariPenuhAgenda) {
            $alpaHariIni = 0;
        } else {
            // LAPIS 3 & 4: Jika hari kerja normal, kroscek dengan matriks jadwal & agenda parsial kelas
            $tahunAjaranId = TahunAjaran::aktifId();
            $namaHari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'][today('Asia/Jakarta')->format('l')];

            $jadwalHariIni = JadwalPelajaran::where('hari', $namaHari)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->whereHas('plotingGuru', fn($q) => $q->where('pegawai_id', $pegawaiId))
                ->get();

            if ($jadwalHariIni->isNotEmpty()) {
                $jurnalSudahDiisi = JurnalMengajar::where('pegawai_id', $pegawaiId)
                    ->whereDate('tanggal', today('Asia/Jakarta'))
                    ->pluck('jam_ke')
                    ->toArray();

                $jamJurnalFlat = [];
                foreach ($jurnalSudahDiisi as $jamKe) {
                    foreach (JamPelajaranHelper::parse($jamKe) as $j) {
                        $jamJurnalFlat[] = $j;
                    }
                }

                foreach ($jadwalHariIni as $j) {
                    // Cek Lapis 3: Apakah kelas pada jadwal ini sedang dikunci oleh agenda kondisi_kelas?
                    $isKelasAdaAgenda = AgendaMadrasah::aktifPada(today('Asia/Jakarta'))
                        ->where('tipe_agenda', 'kondisi_kelas')
                        ->where('kelas_id', $j->kelas_id)
                        ->exists();

                    // Jika kelas sedang outbound/kegiatan khusus, lewati (jangan dihitung alpa)
                    if ($isKelasAdaAgenda) {
                        continue;
                    }

                    // Jika kelas normal berjalan, cek apakah guru sudah isi jurnal
                    foreach (JamPelajaranHelper::parse($j->jam_ke) as $jamIdx) {
                        if (!in_array($jamIdx, $jamJurnalFlat)) {
                            $alpaHariIni++;
                        }
                    }
                }
            }
        }

        // Style Preset Premium
        $premiumStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);'
        ];

        // Style Alarm (Blinking Red)
        $alarmStyle = [
            'style' => 'border-radius: 1rem; border: 1px solid #fecaca; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);'
        ];

        return [
            Stat::make('Jurnal Hari Ini', $alpaHariIni > 0 ? "Kurang {$alpaHariIni} Jam" : (string) $jurnalHariIni)
                ->description(
                    $hariPenuhAgenda
                        ? "Agenda: {$pesanAgendaPusat}"
                        : ($alpaHariIni > 0 ? 'Segera lengkapi jurnal Anda!' : ($jurnalHariIni > 0 ? 'Semua jadwal hari ini tuntas' : 'Tidak ada jadwal mengajar hari ini'))
                )
                ->descriptionIcon($hariPenuhAgenda ? 'heroicon-m-flag' : ($alpaHariIni > 0 ? 'heroicon-m-exclamation-triangle' : ($jurnalHariIni > 0 ? 'heroicon-m-check-badge' : 'heroicon-m-calendar')))
                ->icon($hariPenuhAgenda ? 'heroicon-o-gift' : ($alpaHariIni > 0 ? 'heroicon-o-shield-exclamation' : 'heroicon-o-book-open'))
                ->color($hariPenuhAgenda ? 'info' : ($alpaHariIni > 0 ? 'danger' : ($jurnalHariIni > 0 ? 'success' : 'gray')))
                ->chart([0, 1, 2, 3, 4, $jurnalHariIni ?: 0])
                ->extraAttributes($alpaHariIni > 0 ? $alarmStyle : $premiumStyle),

            Stat::make('Jurnal Bulan Ini', (string) $jurnalBulanIni)
                ->description('Total sesi mengajar di ' . now('Asia/Jakarta')->translatedFormat('F'))
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
        ];
    }
}
