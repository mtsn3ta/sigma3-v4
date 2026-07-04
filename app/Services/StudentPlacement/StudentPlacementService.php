<?php

namespace App\Services\StudentPlacement;

use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;

class StudentPlacementService
{
    public function assign(
        int $kelasId,
        array $siswaIds,
    ): array {

        $tahunAjaranId = TahunAjaran::aktifId();

        if (! $tahunAjaranId) {
            throw new \RuntimeException('Tidak ada Tahun Ajaran aktif.');
        }
        $created = 0;

        $skipped = 0;
        DB::transaction(function () use (
            $kelasId,
            $siswaIds,
            $tahunAjaranId,
            &$created,
            &$skipped,
        ) {

            foreach ($siswaIds as $siswaId) {

                $exists = KelasSiswa::query()

                    ->where('siswa_id', $siswaId)

                    ->where(
                        'tahun_ajaran_id',
                        $tahunAjaranId,
                    )

                    ->exists();

                if ($exists) {

                    $skipped++;

                    continue;
                }

                KelasSiswa::create([

                    'siswa_id' => $siswaId,

                    'kelas_id' => $kelasId,

                    'tahun_ajaran_id' => $tahunAjaranId,

                ]);
                $created++;
            }
        });
        return [

            'created' => $created,

            'skipped' => $skipped,

        ];
    }
    public function move(
        KelasSiswa $placement,
        int $kelasId,
    ): array {
        if ($placement->kelas_id === $kelasId) {

            return [
                'changed' => false,
            ];
        }

        $placement->update([
            'kelas_id' => $kelasId,
        ]);

        return [
            'changed' => true,
        ];
    }
}
