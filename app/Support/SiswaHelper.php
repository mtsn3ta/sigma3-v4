<?php

namespace App\Support;

use App\Models\KelasSiswa;

class SiswaHelper
{
    /**
     * Daftar siswa aktif berdasarkan kelas.
     */
    public static function byKelas(?int $kelasId): array
    {
        if (!$kelasId) {
            return [];
        }

        return KelasSiswa::query()

            ->where('kelas_id', $kelasId)

            ->where('status', 'aktif')

            ->with('siswa')

            ->get()

            ->pluck('siswa.nama', 'siswa.id')

            ->toArray();
    }
}
