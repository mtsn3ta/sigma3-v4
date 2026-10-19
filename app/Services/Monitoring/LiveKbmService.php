<?php

namespace App\Services\Monitoring;

use App\Models\AgendaMadrasah;
use App\Models\JadwalPelajaran;
use App\Models\JurnalMengajar;
use App\Models\Kelas;
use App\Models\Pegawai;
use App\Models\PresensiGuruKelas;
use App\Models\TahunAjaran;
use App\Models\TugasGuruIzin;
use App\Support\JamPelajaranHelper;
use Carbon\Carbon;

class LiveKbmService
{
    /**
     * Mendapatkan data monitoring KBM real-time seluruh kelas untuk display TV.
     */
    public function getLiveMonitoringData(?Carbon $now = null): array
    {
        $now = $now ? $now->copy() : Carbon::now('Asia/Jakarta');
        $slot = JamPelajaranHelper::getCurrentSlot($now);
        $tanggalStr = $now->toDateString();
        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $namaHari = $hariMap[$now->format('l')] ?? 'Senin';
        $tahunAjaranId = TahunAjaran::aktifId();

        // Ambil seluruh kelas aktif
        $kelasList = Kelas::query()
            ->when(
                Kelas::where('status', 'aktif')->exists(),
                fn ($q) => $q->where('status', 'aktif')
            )
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        // Cek Agenda / Libur Umum Madrasah
        $agendaPusat = null;
        if (class_exists(AgendaMadrasah::class)) {
            $agendaPusat = AgendaMadrasah::whereDate('tanggal', $tanggalStr)
                ->whereIn('tipe_agenda', ['libur_nasional', 'kegiatan_bersama'])
                ->first();
        }

        $isSunday = $now->isSunday();
        $isHoliday = $isSunday || !empty($agendaPusat);
        $holidayLabel = $isSunday
            ? 'Libur Akhir Pekan (Minggu)'
            : ($agendaPusat ? strtoupper($agendaPusat->nama_agenda) : null);

        // Jika Libur atau Di luar jam KBM atau Istirahat/Ubudiyah
        $currentJamKe = $slot['jam_ke'] ?? null;
        $isKbm = (bool) ($slot['is_kbm'] ?? false);

        // Eager load data hari ini jika pada jam KBM
        $jadwalToday = collect();
        $presensiToday = collect();
        $jurnalToday = collect();
        $izinToday = collect();

        if ($isKbm && $currentJamKe) {
            $jadwalToday = JadwalPelajaran::with(['plotingGuru.pegawai'])
                ->where('hari', $namaHari)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->get();

            $presensiToday = PresensiGuruKelas::with('pegawai')
                ->whereDate('tanggal', $tanggalStr)
                ->get();

            $jurnalToday = JurnalMengajar::with('pegawai')
                ->whereDate('tanggal', $tanggalStr)
                ->get();

            $izinToday = TugasGuruIzin::with('guru')
                ->whereDate('tanggal', $tanggalStr)
                ->get();
        }

        $items = [];
        $totalHadir = 0;
        $totalBelumMasuk = 0;
        $totalIzin = 0;
        $totalKosong = 0;

        foreach ($kelasList as $kelas) {
            $kelasId = $kelas->id;

            // Jika Libur
            if ($isHoliday) {
                $items[] = [
                    'kelas_id' => $kelasId,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat' => $kelas->tingkat,
                    'qr_token' => $kelas->qr_token,
                    'status_code' => 'LIBUR',
                    'status_label' => 'Libur',
                    'badge_color' => 'gray',
                    'guru_nama' => '-',
                    'guru_foto' => null,
                    'mapel' => $holidayLabel,
                    'waktu_presensi' => null,
                    'metode' => null,
                    'is_jurnal_filled' => false,
                    'foto_kbm' => null,
                    'keterangan' => $agendaPusat?->keterangan ?: 'Tidak ada aktivitas KBM',
                ];
                continue;
            }

            // Jika Istirahat / Ubudiyah / Apel
            if (!empty($slot['is_break'])) {
                $items[] = [
                    'kelas_id' => $kelasId,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat' => $kelas->tingkat,
                    'qr_token' => $kelas->qr_token,
                    'status_code' => 'BREAK',
                    'status_label' => $slot['label'],
                    'badge_color' => 'blue',
                    'guru_nama' => '-',
                    'guru_foto' => null,
                    'mapel' => $slot['label'],
                    'waktu_presensi' => null,
                    'metode' => null,
                    'is_jurnal_filled' => false,
                    'foto_kbm' => null,
                    'keterangan' => 'Waktu ' . $slot['label'] . ' (' . $slot['start'] . ' - ' . $slot['end'] . ')',
                ];
                continue;
            }

            // Jika di luar jam (sebelum KBM atau selesai KBM)
            if (!$isKbm) {
                $items[] = [
                    'kelas_id' => $kelasId,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat' => $kelas->tingkat,
                    'qr_token' => $kelas->qr_token,
                    'status_code' => 'STANDBY',
                    'status_label' => $slot['label'],
                    'badge_color' => 'gray',
                    'guru_nama' => '-',
                    'guru_foto' => null,
                    'mapel' => '-',
                    'waktu_presensi' => null,
                    'metode' => null,
                    'is_jurnal_filled' => false,
                    'foto_kbm' => null,
                    'keterangan' => $slot['label'],
                ];
                continue;
            }

            // ==================== SAAT JAM KBM AKTIF ====================
            // 1. Cari Jadwal Pelajaran untuk kelas ini di jam_ke ini
            $jadwal = $jadwalToday->first(function ($j) use ($kelasId, $currentJamKe) {
                if ($j->kelas_id != $kelasId) return false;
                $slotIndices = JamPelajaranHelper::parse($j->jam_ke);
                return in_array((int) $currentJamKe, $slotIndices, true);
            });

            // 2. Cari Presensi Guru di Kelas
            $presensi = $presensiToday->first(function ($p) use ($kelasId, $currentJamKe) {
                if ($p->kelas_id != $kelasId) return false;
                $indices = JamPelajaranHelper::parse($p->jam_ke);
                return in_array((int) $currentJamKe, $indices, true);
            });

            // 3. Cari Jurnal Mengajar
            $jurnal = $jurnalToday->first(function ($jm) use ($kelasId, $currentJamKe) {
                if ($jm->kelas_id != $kelasId) return false;
                $indices = JamPelajaranHelper::parse($jm->jam_ke);
                return in_array((int) $currentJamKe, $indices, true);
            });

            // 4. Cari Izin Guru
            $izin = $izinToday->first(function ($iz) use ($kelasId, $currentJamKe) {
                if ($iz->kelas_id != $kelasId) return false;
                $indices = JamPelajaranHelper::parse($iz->jam_ke);
                return in_array((int) $currentJamKe, $indices, true);
            });

            $guruTerjadwal = $jadwal?->plotingGuru?->pegawai;
            $mapelTerjadwal = $jadwal?->plotingGuru?->mapel ?? '-';

            // Penentuan Status Kelas
            if ($presensi || $jurnal) {
                // GURU SUDAH HADIR DI KELAS
                $totalHadir++;
                $pegawai = $presensi?->pegawai ?? $jurnal?->pegawai ?? $guruTerjadwal;
                $waktuCheckin = $presensi?->waktu_masuk ? Carbon::parse($presensi->waktu_masuk)->format('H:i') : null;
                $metode = $presensi?->metode ?? 'jurnal';
                $isJurnalFilled = !empty($jurnal);
                $isPengganti = (bool) ($presensi?->is_guru_pengganti ?? false);

                $items[] = [
                    'kelas_id' => $kelasId,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat' => $kelas->tingkat,
                    'qr_token' => $kelas->qr_token,
                    'status_code' => $isJurnalFilled ? 'HADIR_LENGKAP' : 'HADIR',
                    'status_label' => $isJurnalFilled ? 'Mengajar & Jurnal OK' : 'Sedang Mengajar',
                    'badge_color' => 'green',
                    'guru_nama' => $pegawai?->nama ?? 'Guru Pengajar',
                    'guru_foto' => $pegawai?->foto ? asset('storage/' . $pegawai->foto) : null,
                    'mapel' => $jurnal?->mapel ?? $mapelTerjadwal,
                    'waktu_presensi' => $waktuCheckin,
                    'metode' => $metode,
                    'is_jurnal_filled' => $isJurnalFilled,
                    'is_pengganti' => $isPengganti,
                    'foto_kbm' => $jurnal?->dokumentasi_foto ? asset('storage/' . $jurnal->dokumentasi_foto) : null,
                    'keterangan' => $isJurnalFilled
                        ? 'Jurnal terisi: ' . ($jurnal->materi ?: 'KBM Terlaksana')
                        : 'Check-in ' . ($waktuCheckin ?: '') . ' via ' . strtoupper($metode),
                ];
            } elseif ($izin) {
                // GURU TERCATAT IZIN
                $totalIzin++;
                $items[] = [
                    'kelas_id' => $kelasId,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat' => $kelas->tingkat,
                    'qr_token' => $kelas->qr_token,
                    'status_code' => 'IZIN',
                    'status_label' => 'Guru Izin',
                    'badge_color' => 'yellow',
                    'guru_nama' => $izin->guru?->nama ?? $guruTerjadwal?->nama ?? '-',
                    'guru_foto' => $izin->guru?->foto ? asset('storage/' . $izin->guru->foto) : null,
                    'mapel' => $izin->mapel ?: $mapelTerjadwal,
                    'waktu_presensi' => null,
                    'metode' => null,
                    'is_jurnal_filled' => false,
                    'is_pengganti' => false,
                    'foto_kbm' => null,
                    'keterangan' => 'Tugas Izin: ' . ($izin->isi_tugas ?: 'Diberikan tugas mandiri'),
                ];
            } elseif ($jadwal) {
                // ADA JADWAL TAPI BELUM ADA PRESENSI (BELUM MASUK)
                $totalBelumMasuk++;
                $elapsed = $slot['elapsed_minutes'] ?? 0;
                $isGracePeriod = $elapsed <= 10;

                $items[] = [
                    'kelas_id' => $kelasId,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat' => $kelas->tingkat,
                    'qr_token' => $kelas->qr_token,
                    'status_code' => $isGracePeriod ? 'MENUNGGU' : 'BELUM_MASUK',
                    'status_label' => $isGracePeriod ? 'Menunggu Guru' : 'Belum Masuk',
                    'badge_color' => $isGracePeriod ? 'amber' : 'red',
                    'guru_nama' => $guruTerjadwal?->nama ?? 'Jadwal: Guru Belum Dipetakan',
                    'guru_foto' => $guruTerjadwal?->foto ? asset('storage/' . $guruTerjadwal->foto) : null,
                    'mapel' => $mapelTerjadwal,
                    'waktu_presensi' => null,
                    'metode' => null,
                    'is_jurnal_filled' => false,
                    'is_pengganti' => false,
                    'foto_kbm' => null,
                    'keterangan' => $isGracePeriod
                        ? 'Menit ke-' . $elapsed . ' (Toleransi pergantian jam)'
                        : 'Lewat ' . $elapsed . ' menit, belum ada presensi',
                ];
            } else {
                // MEMANG JAM KOSONG TIDAK ADA JADWAL
                $totalKosong++;
                $items[] = [
                    'kelas_id' => $kelasId,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tingkat' => $kelas->tingkat,
                    'qr_token' => $kelas->qr_token,
                    'status_code' => 'KOSONG',
                    'status_label' => 'Jam Kosong',
                    'badge_color' => 'gray',
                    'guru_nama' => '-',
                    'guru_foto' => null,
                    'mapel' => 'Tidak Ada Jadwal',
                    'waktu_presensi' => null,
                    'metode' => null,
                    'is_jurnal_filled' => false,
                    'is_pengganti' => false,
                    'foto_kbm' => null,
                    'keterangan' => 'Slot bebas / jam kosong',
                ];
            }
        }

        // Kelompokkan kelas berdasarkan tingkat (Kelas 7, Kelas 8, Kelas 9)
        $groupedByTingkat = collect($items)->groupBy('tingkat')->all();

        return [
            'timestamp' => $now->toIso8601String(),
            'waktu_wib' => $now->format('H:i:s'),
            'tanggal_id' => $now->isoFormat('dddd, D MMMM Y'),
            'slot' => $slot,
            'summary' => [
                'total_kelas' => count($kelasList),
                'total_hadir' => $totalHadir,
                'total_belum_masuk' => $totalBelumMasuk,
                'total_izin' => $totalIzin,
                'total_kosong' => $totalKosong,
                'persentase_kehadiran' => count($kelasList) > 0 && ($totalHadir + $totalBelumMasuk) > 0
                    ? round(($totalHadir / ($totalHadir + $totalBelumMasuk)) * 100, 1)
                    : 0,
            ],
            'items' => $items,
            'grouped' => $groupedByTingkat,
        ];
    }
}
