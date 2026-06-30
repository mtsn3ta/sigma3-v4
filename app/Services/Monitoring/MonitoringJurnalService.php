<?php

namespace App\Services\Monitoring;

use App\Models\JurnalMengajar;
use App\Models\KehadiranSekolah;
use App\Models\TugasGuruIzin;
use App\Support\JamPelajaranHelper;
use Carbon\Carbon;

class MonitoringJurnalService
{
    public function buildTimeline(
        Carbon $tanggal,
        int $kelasId,
    ): array {

        $timeline = [];

        /*
        |--------------------------------------------------------------------------
        | Jurnal Mengajar
        |--------------------------------------------------------------------------
        */

        $jurnal = JurnalMengajar::query()

            ->with([
                'pegawai',
                'kehadiranPembelajaran.siswa',
            ])

            ->whereDate(
                'tanggal',
                $tanggal
            )

            ->where(
                'kelas_id',
                $kelasId
            )

            ->get();

        /*
        |--------------------------------------------------------------------------
        | Tugas Guru Izin
        |--------------------------------------------------------------------------
        */

        $izin = TugasGuruIzin::query()

            ->with('guru')

            ->whereDate(
                'tanggal',
                $tanggal
            )

            ->where(
                'kelas_id',
                $kelasId
            )

            ->get();

        /*
        |--------------------------------------------------------------------------
        | Kehadiran Sekolah (sekali query)
        |--------------------------------------------------------------------------
        */

        $kehadiranSekolah = KehadiranSekolah::query()

            ->with('siswa')

            ->whereDate(
                'tanggal',
                $tanggal
            )

            ->whereIn('status', [
                'izin',
                'sakit',
                'alfa',
            ])

            ->whereHas('siswa.kelasAktif', function ($query) use ($kelasId) {

                $query->where(
                    'kelas_id',
                    $kelasId
                );
            })

            ->get();

        $ketidakhadiranSekolah = [];

        foreach ($kehadiranSekolah as $absen) {

            $kode = match ($absen->status) {

                'izin' => 'I',

                'sakit' => 'S',

                'alfa' => 'A',

                default => '?',
            };

            $ketidakhadiranSekolah[] =
                $absen->siswa->nama .
                " ({$kode})";
        }

        /*
        |--------------------------------------------------------------------------
        | Timeline Jurnal Mengajar
        |--------------------------------------------------------------------------
        */

        foreach ($jurnal as $item) {

            $tidakHadir = $ketidakhadiranSekolah;

            foreach ($item->kehadiranPembelajaran as $absen) {

                $kode = match ($absen->status) {

                    'bolos'      => 'B',

                    'ke_bk'      => 'BK',

                    'ke_uks'     => 'UKS',

                    'dispensasi' => 'D',

                    'lomba'      => 'L',

                    'lainnya'    => 'Ln',

                    default      => '?',
                };

                $tidakHadir[] =
                    $absen->siswa->nama .
                    " ({$kode})";
            }

            $timeline[$item->jam_ke] = [

                'jam' => $item->jam_ke,

                'status' => 'mengajar',

                'guru' => $item->pegawai->nama,

                'mapel' => $item->mapel,

                'materi' => $item->materi,

                'tidak_hadir' =>

                count($tidakHadir)

                    ? implode(', ', $tidakHadir)

                    : '-',

                'catatan' =>

                $item->catatan ?: '-',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Timeline Guru Izin
        |--------------------------------------------------------------------------
        */

        foreach ($izin as $item) {

            if (isset($timeline[$item->jam_ke])) {
                continue;
            }

            $timeline[$item->jam_ke] = [

                'jam' => $item->jam_ke,

                'status' => 'izin',

                'guru' => $item->guru->nama,

                'mapel' => $item->mapel,

                'materi' => $item->isi_tugas,

                'tidak_hadir' =>

                count($ketidakhadiranSekolah)

                    ? implode(', ', $ketidakhadiranSekolah)

                    : '-',

                'catatan' => '-',

            ];
        }

        /*
|--------------------------------------------------------------------------
| Slot Jam Kosong
|--------------------------------------------------------------------------
*/

        $maxJam = JamPelajaranHelper::maxJam($tanggal);

        /*
|--------------------------------------------------------------------------
| Cari jam yang sudah terpakai
|--------------------------------------------------------------------------
*/

        $jamTerpakai = [];

        foreach (array_keys($timeline) as $jamKe) {

            foreach (JamPelajaranHelper::parse($jamKe) as $jam) {

                $jamTerpakai[] = $jam;
            }
        }

        for ($i = 1; $i <= $maxJam; $i++) {

            if (in_array($i, $jamTerpakai, true)) {
                continue;
            }

            $timeline[(string) $i] = [

                'jam' => (string) $i,

                'status' => 'kosong',

                'guru' => '-',

                'mapel' => '-',

                'materi' => '-',

                'tidak_hadir' =>

                count($ketidakhadiranSekolah)

                    ? implode(', ', $ketidakhadiranSekolah)

                    : '-',

                'catatan' => '-',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Urutkan Timeline
        |--------------------------------------------------------------------------
        */

        uksort(
            $timeline,
            fn($a, $b) =>
            JamPelajaranHelper::sortKey($a)
                <=>
                JamPelajaranHelper::sortKey($b)
        );

        return array_values(
            $timeline
        );
    }
}
