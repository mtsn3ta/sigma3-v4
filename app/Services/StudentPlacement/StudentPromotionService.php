<?php

namespace App\Services\StudentPlacement;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\AnggotaEkskul;
use App\Models\TahunAjaran;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentPromotionService
{
    public function prepare(
        int $tahunAsalId,
        int $tahunTujuanId,
    ): array {

        if ($tahunAsalId === $tahunTujuanId) {

            throw ValidationException::withMessages([

                'tahun_tujuan_id' => 'Tahun Ajaran tujuan harus berbeda.',

            ]);
        }

        $tahunAsal = TahunAjaran::findOrFail($tahunAsalId);

        $tahunTujuan = TahunAjaran::findOrFail($tahunTujuanId);

        $sudahDiproses = KelasSiswa::query()

            ->where(
                'tahun_ajaran_id',
                $tahunTujuan->id,
            )

            ->exists();

        if ($sudahDiproses) {

            throw ValidationException::withMessages([

                'tahun_tujuan_id' => 'Tahun Ajaran tujuan sudah memiliki data penempatan siswa.',

            ]);
        }

        return [

            'tahun_asal' => $tahunAsal,

            'tahun_tujuan' => $tahunTujuan,

        ];
    }

    public function generate(
        int $tahunAsalId,
    ): array {

        $placements = KelasSiswa::query()

            ->with([
                'kelas',
                'siswa',
            ])

            ->where(
                'tahun_ajaran_id',
                $tahunAsalId,
            )
            ->whereHas(
                'siswa',
                fn($query) =>
                $query->where('status', 'aktif')
            )
            ->orderBy('kelas_id')

            ->get();

        $result = [];

        foreach ($placements as $placement) {

            $kelasAsal = $placement->kelas;

            /*
            |--------------------------------------------------------------------------
            | Kelas 9 = Lulus
            |--------------------------------------------------------------------------
            */

            if ($kelasAsal->tingkat === 9) {

                $result[] = [

                    'siswa_id' => $placement->siswa_id,

                    'nama' => $placement->siswa->nama,

                    'kelas_asal_id' => $kelasAsal->id,

                    'kelas_asal' => $kelasAsal->nama_kelas,

                    'kelas_tujuan_id' => null,

                    'kelas_tujuan' => 'Lulus',

                    'status' => 'lulus',

                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Cari Kelas Tujuan
            |--------------------------------------------------------------------------
            */

            $namaKelasTujuan = str_replace(

                (string) $kelasAsal->tingkat,

                (string) ($kelasAsal->tingkat + 1),

                $kelasAsal->nama_kelas,

            );

            $kelasTujuan = Kelas::query()

                ->where(
                    'tingkat',
                    $kelasAsal->tingkat + 1,
                )

                ->where(
                    'nama_kelas',
                    $namaKelasTujuan,
                )

                ->first();

            if (! $kelasTujuan) {

                throw ValidationException::withMessages([

                    'kelas' => sprintf(
                        'Kelas tujuan %s tidak ditemukan.',
                        $namaKelasTujuan,
                    ),

                ]);
            }

            $result[] = [

                'siswa_id' => $placement->siswa_id,

                'nama' => $placement->siswa->nama,

                'kelas_asal_id' => $kelasAsal->id,

                'kelas_asal' => $kelasAsal->nama_kelas,

                'kelas_tujuan_id' => $kelasTujuan->id,

                'kelas_tujuan' => $kelasTujuan->nama_kelas,

                'status' => 'naik',

            ];
        }

        return $result;
    }

    public function execute(
        int $tahunAsalId,
        int $tahunTujuanId,
    ): array {

        $data = $this->prepare(
            $tahunAsalId,
            $tahunTujuanId,
        );

        $tahunAsal = $data['tahun_asal'];

        $tahunTujuan = $data['tahun_tujuan'];

        if (! $tahunAsal->aktif) {

            throw ValidationException::withMessages([

                'tahun_asal_id' => 'Tahun Ajaran asal harus berstatus aktif.',

            ]);
        }

        $placements = $this->generate(
            $tahunAsalId,
        );

        if (empty($placements)) {

            throw ValidationException::withMessages([

                'tahun_asal_id' => 'Tidak ada siswa yang dapat diproses.',

            ]);
        }

        $naik = 0;

        $lulus = 0;

        DB::transaction(function () use (

            $placements,

            $tahunTujuan,

            &$naik,

            &$lulus,

            $tahunAsal,

        ) {

            foreach ($placements as $placement) {

                /*
                |--------------------------------------------------------------------------
                | Lulus
                |--------------------------------------------------------------------------
                */

                if ($placement['status'] === 'lulus') {

                    Siswa::whereKey(
                        $placement['siswa_id']
                    )->update([

                        'status' => 'lulus',

                    ]);

                    $lulus++;

                    continue;
                }

                /*
|--------------------------------------------------------------------------
| Naik Kelas
|--------------------------------------------------------------------------
*/

                KelasSiswa::create([

                    'siswa_id' => $placement['siswa_id'],

                    'kelas_id' => $placement['kelas_tujuan_id'],

                    'tahun_ajaran_id' => $tahunTujuan->id,

                ]);

                /*
|--------------------------------------------------------------------------
| Salin Anggota Ekstrakurikuler
|--------------------------------------------------------------------------
*/

                $anggotaEkskul = AnggotaEkskul::query()

                    ->where('tahun_ajaran_id', $tahunAsal->id)

                    ->where('siswa_id', $placement['siswa_id'])

                    ->get();

                foreach ($anggotaEkskul as $anggota) {

                    AnggotaEkskul::firstOrCreate(

                        [

                            'siswa_id' => $placement['siswa_id'],

                            'ekstrakurikuler_id' => $anggota->ekstrakurikuler_id,

                            'tahun_ajaran_id' => $tahunTujuan->id,

                        ],

                        [

                            'tanggal_daftar' => $anggota->tanggal_daftar,

                            'status' => 'aktif',

                        ]

                    );
                }

                $naik++;
            }
            /*
|--------------------------------------------------------------------------
| Update Status Tahun Ajaran
|--------------------------------------------------------------------------
*/

            $tahunAsal->update([
                'aktif' => false,
            ]);

            $tahunTujuan->update([
                'aktif' => true,
            ]);
        });
        ActivityLogService::log(

            'Kenaikan Tahun Ajaran',

            sprintf(

                '%s → %s | Naik: %d | Lulus: %d',

                $tahunAsal->tahun,

                $tahunTujuan->tahun,

                $naik,

                $lulus,

            )

        );
        return [

            'naik' => $naik,

            'lulus' => $lulus,

        ];
    }
}
