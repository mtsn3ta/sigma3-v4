<?php

namespace App\Services\Monitoring;

use App\Models\JurnalMengajar;
use App\Models\KehadiranSekolah;
use App\Models\TugasGuruIzin;
use App\Models\AgendaMadrasah;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Support\JamPelajaranHelper;
use Carbon\Carbon;

class MonitoringJurnalService
{
    public function buildTimeline(
        Carbon $tanggal,
        int $kelasId,
    ): array {

        /*
        |--------------------------------------------------------------------------
        | MASTERPLAN LAPIS 1, 2, & 3: CEGAH JADWAL JIKA LIBUR / ADA AGENDA
        |--------------------------------------------------------------------------
        */

        // Lapis 1: Hari Minggu
        if ($tanggal->isSunday()) {
            return [[
                'jam' => 'Full',
                'status' => 'libur',
                'guru' => '-',
                'mapel' => '-',
                'materi' => 'LIBUR AKHIR PEKAN',
                'tidak_hadir' => '-',
                'catatan' => '-',
            ]];
        }

        if (class_exists(AgendaMadrasah::class)) {
            // Lapis 2: Libur Nasional / Kegiatan Bersama
            $agendaPusat = AgendaMadrasah::whereDate('tanggal', $tanggal->toDateString())
                ->whereIn('tipe_agenda', ['libur_nasional', 'kegiatan_bersama'])
                ->first();

            if ($agendaPusat) {
                return [[
                    'jam' => 'Full',
                    'status' => 'libur',
                    'guru' => '-',
                    'mapel' => 'Agenda Sekolah',
                    'materi' => strtoupper($agendaPusat->nama_agenda),
                    'tidak_hadir' => '-',
                    'catatan' => $agendaPusat->keterangan ?: '-',
                ]];
            }

            // Lapis 3: Kondisi Parsial Kelas (Misal: Outbound Kelas 7)
            $agendaKelas = AgendaMadrasah::whereDate('tanggal', $tanggal->toDateString())
                ->where('tipe_agenda', 'kondisi_kelas')
                ->where('kelas_id', $kelasId)
                ->first();

            if ($agendaKelas) {
                return [[
                    'jam' => 'Full',
                    'status' => 'kegiatan',
                    'guru' => '-',
                    'mapel' => 'Agenda Kelas',
                    'materi' => strtoupper($agendaKelas->nama_agenda),
                    'tidak_hadir' => '-',
                    'catatan' => $agendaKelas->keterangan ?: '-',
                ]];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | LAPIS 4: JADWAL NORMAL (Jurnal, Izin, Kosong, & Lupa Isi Jurnal)
        |--------------------------------------------------------------------------
        */

        $timeline = [];

        // 1. Ambil Data Jurnal Mengajar
        $jurnal = JurnalMengajar::query()
            ->with(['pegawai', 'kehadiranPembelajaran.siswa'])
            ->whereDate('tanggal', $tanggal)
            ->where('kelas_id', $kelasId)
            ->get();

        // 2. Ambil Data Guru Izin
        $izin = TugasGuruIzin::query()
            ->with('guru')
            ->whereDate('tanggal', $tanggal)
            ->where('kelas_id', $kelasId)
            ->get();

        // 3. Ambil Data Kehadiran Sekolah Siswa
        $kehadiranSekolah = KehadiranSekolah::query()
            ->with('siswa')
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', ['izin', 'sakit', 'alfa'])
            ->whereHas('siswa.kelasAktif', fn($q) => $q->where('kelas_id', $kelasId))
            ->get();

        $ketidakhadiranSekolah = [];
        foreach ($kehadiranSekolah as $absen) {
            $kode = match ($absen->status) {
                'izin' => 'I',
                'sakit' => 'S',
                'alfa' => 'A',
                default => '?',
            };
            $ketidakhadiranSekolah[] = $absen->siswa->nama . " ({$kode})";
        }

        // Susun Jurnal Mengajar ke Timeline
        foreach ($jurnal as $item) {
            $tidakHadir = $ketidakhadiranSekolah;
            foreach ($item->kehadiranPembelajaran as $absen) {
                $kode = match ($absen->status) {
                    'bolos' => 'B',
                    'ke_bk' => 'BK',
                    'ke_uks' => 'UKS',
                    'dispensasi' => 'D',
                    'lomba' => 'L',
                    'lainnya' => 'Ln',
                    default => '?',
                };
                $tidakHadir[] = $absen->siswa->nama . " ({$kode})";
            }

            $timeline[$item->jam_ke] = [
                'id' => $item->id,
                'jam' => $item->jam_ke,
                'status' => 'mengajar',
                'guru' => $item->pegawai->nama,
                'mapel' => $item->mapel,
                'materi' => $item->materi,
                'tidak_hadir' => count($tidakHadir) ? implode(', ', $tidakHadir) : '-',
                'catatan' => $item->catatan ?: '-',
                'dokumentasi_foto' => $item->dokumentasi_foto,
                'dokumentasi_video' => $item->dokumentasi_video,
            ];
        }

        // Susun Guru Izin ke Timeline
        foreach ($izin as $item) {
            if (isset($timeline[$item->jam_ke])) continue;
            $timeline[$item->jam_ke] = [
                'jam' => $item->jam_ke,
                'status' => 'izin',
                'guru' => $item->guru->nama,
                'mapel' => $item->mapel,
                'materi' => $item->isi_tugas,
                'tidak_hadir' => count($ketidakhadiranSekolah) ? implode(', ', $ketidakhadiranSekolah) : '-',
                'catatan' => 'Tugas Izin',
            ];
        }

        // Kroscek dengan Jadwal Pelajaran untuk mencari "Siapa yang lupa isi jurnal?"
        $namaHari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'][$tanggal->format('l')];
        $tahunAjaranId = TahunAjaran::aktifId();

        $jadwalHariIni = JadwalPelajaran::with('plotingGuru.pegawai')
            ->where('hari', $namaHari)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('kelas_id', $kelasId)
            ->get();

        $jadwalLookup = [];
        foreach ($jadwalHariIni as $j) {
            foreach (JamPelajaranHelper::parse($j->jam_ke) as $jamIdx) {
                $jadwalLookup[$jamIdx] = $j;
            }
        }

        // ================= PERBAIKAN UTAMA: ANTI JAM GAIB =================
        // Tarik batas maksimal jam pelajaran aktual seluruh sekolah pada hari ini saja
        $maxJam = JadwalPelajaran::where('hari', $namaHari)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->pluck('jam_ke')
            ->flatMap(fn($jamKe) => JamPelajaranHelper::parse($jamKe))
            ->max() ?? JamPelajaranHelper::maxJam($tanggal); // Fallback ke helper jika tabel kosong

        $jamTerpakai = [];
        foreach (array_keys($timeline) as $jamKe) {
            foreach (JamPelajaranHelper::parse($jamKe) as $jam) {
                $jamTerpakai[] = $jam;
            }
        }

        // Isi slot kosong / lupa isi jurnal (Dibatasi oleh maxJam aktual hari ini)
        for ($i = 1; $i <= $maxJam; $i++) {
            if (in_array($i, $jamTerpakai, true)) continue;

            if (isset($jadwalLookup[$i])) {
                // Ada di Jadwal Pelajaran, tapi tidak ada di Jurnal!
                $jadwal = $jadwalLookup[$i];
                $timeline[(string) $i] = [
                    'jam' => (string) $i,
                    'status' => 'alpa',
                    'guru' => $jadwal->plotingGuru->pegawai->nama ?? '-',
                    'mapel' => $jadwal->plotingGuru->mapel ?? '-',
                    'materi' => 'BELUM MENGISI JURNAL',
                    'tidak_hadir' => count($ketidakhadiranSekolah) ? implode(', ', $ketidakhadiranSekolah) : '-',
                    'catatan' => 'Menunggu input guru',
                ];
            } else {
                // Benar-benar Jam Kosong
                $timeline[(string) $i] = [
                    'jam' => (string) $i,
                    'status' => 'kosong',
                    'guru' => '-',
                    'mapel' => '-',
                    'materi' => 'Jam Kosong',
                    'tidak_hadir' => count($ketidakhadiranSekolah) ? implode(', ', $ketidakhadiranSekolah) : '-',
                    'catatan' => '-',
                ];
            }
        }

        // Urutkan Timeline
        uksort($timeline, fn($a, $b) => JamPelajaranHelper::sortKey($a) <=> JamPelajaranHelper::sortKey($b));
        return array_values($timeline);
    }

    public function buildTimelineSemuaKelas(Carbon $tanggal): array
    {
        $daftarKelas = Kelas::query()
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        $hasil = [];

        foreach ($daftarKelas as $kelas) {
            $timeline = $this->buildTimeline($tanggal->copy(), $kelas->id);

            $hasil[] = [
                'kelas' => $kelas,
                'timeline' => $timeline,
                'totalMengajar' => collect($timeline)->where('status', 'mengajar')->count(),
                'totalIzin' => collect($timeline)->where('status', 'izin')->count(),
                'totalKosong' => collect($timeline)->where('status', 'kosong')->count(),
                'totalAlpa' => collect($timeline)->where('status', 'alpa')->count(),
            ];
        }

        usort($hasil, function ($a, $b) {
            if ($a['totalAlpa'] !== $b['totalAlpa']) {
                return $b['totalAlpa'] <=> $a['totalAlpa'];
            }
            return $a['kelas']->nama_kelas <=> $b['kelas']->nama_kelas;
        });

        return $hasil;
    }
}
