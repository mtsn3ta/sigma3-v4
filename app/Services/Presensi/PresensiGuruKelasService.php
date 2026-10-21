<?php

namespace App\Services\Presensi;

use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Pegawai;
use App\Models\PresensiGuruKelas;
use App\Models\TahunAjaran;
use App\Support\JamPelajaranHelper;
use Carbon\Carbon;

class PresensiGuruKelasService
{
    /**
     * Melakukan check-in presensi guru di kelas.
     *
     * @param Pegawai $pegawai
     * @param Kelas $kelas
     * @param string $metode ('qr_code', 'rfid', 'jurnal')
     * @param Carbon|null $now
     * @return array
     */
    public function checkin(
        Pegawai $pegawai,
        Kelas $kelas,
        string $metode = 'qr_code',
        ?Carbon $now = null
    ): array {
        $now = $now ? $now->copy() : Carbon::now('Asia/Jakarta');
        $slot = JamPelajaranHelper::getCurrentSlot($now);

        // Jika hari Minggu atau libur
        if ($now->isSunday()) {
            return [
                'success' => false,
                'message' => 'Hari ini hari Minggu (Libur Akhir Pekan), tidak ada jam KBM.',
                'status_code' => 'LIBUR',
            ];
        }

        // Tentukan jam_ke aktif
        $jamKe = $slot['jam_ke'];

        // Jika sedang istirahat atau sebelum KBM, tentukan slot berikutnya atau terdekat
        if (!$slot['is_kbm']) {
            if ($slot['type'] === 'sebelum_kbm' || $slot['type'] === 'apel') {
                $jamKe = '1'; // Masuk lebih awal untuk Jam 1
            } elseif ($slot['type'] === 'selesai_kbm') {
                return [
                    'success' => false,
                    'message' => 'KBM hari ini telah selesai.',
                    'status_code' => 'SELESAI',
                ];
            } else {
                // Sedang istirahat / ubudiyah / zuhur -> cari slot pelajaran terdekat berikutnya
                $slots = JamPelajaranHelper::getScheduleSlots($now);
                $nextSlot = null;
                $currentTime = $now->format('H:i');
                foreach ($slots as $s) {
                    if ($s['type'] === 'pelajaran' && $s['start'] >= $currentTime) {
                        $nextSlot = $s;
                        break;
                    }
                }
                if ($nextSlot) {
                    $jamKe = $nextSlot['jam_ke'];
                } else {
                    $jamKe = '1';
                }
            }
        }

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

        // 1. Cek apakah guru sudah tercatat presensi di kelas ini pada jam_ke ini hari ini
        $existing = PresensiGuruKelas::whereDate('tanggal', $tanggalStr)
            ->where('kelas_id', $kelas->id)
            ->get()
            ->first(function ($p) use ($jamKe) {
                return in_array((int) $jamKe, JamPelajaranHelper::parse($p->jam_ke), true);
            });

        if ($existing) {
            $waktuFormatted = Carbon::parse($existing->waktu_masuk)->format('H:i');
            $existingParsed = JamPelajaranHelper::parse($existing->jam_ke);
            $existingLabel = count($existingParsed) > 1
                ? "Jam Ke-{$existingParsed[0]} s/d " . end($existingParsed)
                : "Jam Ke-{$existing->jam_ke}";

            return [
                'success' => true,
                'already_recorded' => true,
                'message' => "Presensi kelas {$kelas->nama_kelas} ({$existingLabel}) sudah tercatat sebelumnya pada {$waktuFormatted} WIB oleh {$existing->pegawai->nama}.",
                'data' => [
                    'guru' => $existing->pegawai->nama,
                    'kelas' => $kelas->nama_kelas,
                    'jam_ke' => $existing->jam_ke,
                    'waktu' => $waktuFormatted,
                    'metode' => $existing->metode,
                ],
            ];
        }

        // 2. Cari seluruh jadwal pelajaran kelas ini hari ini
        $allJadwalKelasToday = JadwalPelajaran::with('plotingGuru.pegawai')
            ->where('hari', $namaHari)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('kelas_id', $kelas->id)
            ->orderByRaw('CAST(jam_ke AS UNSIGNED) ASC')
            ->get();

        $jadwal = $allJadwalKelasToday->first(function ($j) use ($jamKe) {
            return in_array((int) $jamKe, JamPelajaranHelper::parse($j->jam_ke), true);
        });

        // 3. Deteksi otomatis jam blok berturut-turut (misal jam 5 dan jam 6 diajar oleh guru & mapel yang sama)
        $startJam = (int) $jamKe;
        $endJam = $startJam;
        $isGuruPengganti = false;
        $mapel = '-';

        if ($jadwal) {
            $mapel = $jadwal->plotingGuru?->mapel ?? '-';
            $guruTerjadwalId = $jadwal->plotingGuru?->pegawai_id;

            if ($guruTerjadwalId && $guruTerjadwalId != $pegawai->id) {
                $isGuruPengganti = true;
            } else {
                // Cek jam-jam berikutnya yang berturut-turut untuk guru & mapel yang sama
                while (true) {
                    $nextJam = $endJam + 1;
                    $nextJadwal = $allJadwalKelasToday->first(function ($j) use ($nextJam) {
                        return in_array($nextJam, JamPelajaranHelper::parse($j->jam_ke), true);
                    });

                    if (
                        $nextJadwal &&
                        $nextJadwal->plotingGuru?->pegawai_id === $guruTerjadwalId &&
                        $nextJadwal->plotingGuru?->mapel === $mapel
                    ) {
                        $endJam = $nextJam;
                    } else {
                        break;
                    }
                }
            }
        }

        // Tentukan format jam_ke: '5-6' jika blok atau '5' jika 1 jam
        $recordedJamKe = ($endJam > $startJam) ? "{$startJam}-{$endJam}" : (string) $startJam;
        $jamLabel = ($endJam > $startJam) ? "Jam Ke-{$startJam} s/d {$endJam}" : "Jam Ke-{$startJam}";

        // 4. Simpan Presensi Guru Kelas
        $presensi = PresensiGuruKelas::create([
            'tanggal' => $tanggalStr,
            'jam_ke' => $recordedJamKe,
            'kelas_id' => $kelas->id,
            'pegawai_id' => $pegawai->id,
            'jadwal_pelajaran_id' => $jadwal?->id,
            'metode' => $metode,
            'waktu_masuk' => $now,
            'is_guru_pengganti' => $isGuruPengganti,
            'catatan' => $isGuruPengganti ? 'Guru Pengadil / Pengganti' : 'Hadir Mengajar',
        ]);

        $waktuMasukStr = $now->format('H:i');

        return [
            'success' => true,
            'already_recorded' => false,
            'message' => "Presensi berhasil! Selamat mengajar di Kelas {$kelas->nama_kelas} ({$jamLabel}).",
            'data' => [
                'id' => $presensi->id,
                'guru' => $pegawai->nama,
                'guru_foto' => $pegawai->foto ? asset('storage/' . $pegawai->foto) : null,
                'kelas' => $kelas->nama_kelas,
                'mapel' => $mapel,
                'jam_ke' => $recordedJamKe,
                'waktu' => $waktuMasukStr,
                'metode' => $metode,
                'is_guru_pengganti' => $isGuruPengganti,
            ],
        ];
    }
}
