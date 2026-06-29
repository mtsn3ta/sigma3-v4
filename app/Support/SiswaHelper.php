<?php

namespace App\Support;

use App\Models\KelasSiswa;
use App\Models\Siswa;

class SiswaHelper
{
    /**
     * Daftar siswa aktif berdasarkan kelas.
     */
    public static function byKelas(?int $kelasId): array
    {
        if (! $kelasId) {
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

    /**
     * Collection siswa yang belum melakukan RFID hari ini.
     */
    public static function belumRfid(?int $kelasId = null)
    {
        return Siswa::query()

            ->where('status', 'aktif')

            ->whereHas('kelasAktif', function ($query) use ($kelasId) {

                if ($kelasId) {
                    $query->where('kelas_id', $kelasId);
                }
            })

            ->whereDoesntHave('kehadiranSekolah', function ($query) {

                $query->whereDate('tanggal', today());
            })

            ->orderBy('nama')

            ->get();
    }

    /**
     * Dropdown siswa yang belum RFID.
     */
    public static function belumRfidOptions(?int $kelasId = null): array
    {
        return self::belumRfid($kelasId)

            ->pluck('nama', 'id')

            ->toArray();
    }
}
