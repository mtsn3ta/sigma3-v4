<?php

namespace App\Support;

use App\Models\Kelas;

class KelasHelper
{
    /**
     * Daftar kelas aktif.
     */
    public static function options(): array
    {
        return Kelas::query()

            ->where('status', 'aktif')

            ->orderBy('tingkat')

            ->orderBy('nama_kelas')

            ->pluck('nama_kelas', 'id')

            ->toArray();
    }

    /**
     * Daftar kelas aktif kecuali kelas tertentu.
     */
    public static function optionsExcept(int $kelasId): array
    {
        return Kelas::query()

            ->where('status', 'aktif')

            ->whereKeyNot($kelasId)

            ->orderBy('tingkat')

            ->orderBy('nama_kelas')

            ->pluck('nama_kelas', 'id')

            ->toArray();
    }
}
