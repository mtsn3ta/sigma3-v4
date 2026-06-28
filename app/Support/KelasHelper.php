<?php

namespace App\Support;

use App\Models\Kelas;

class KelasHelper
{
    /**
     * Daftar kelas aktif.
     */
    public static function options()
    {
        return Kelas::query()

            ->where('status', 'aktif')

            ->orderBy('tingkat')

            ->orderBy('nama_kelas')

            ->pluck('nama_kelas', 'id');
    }
}
