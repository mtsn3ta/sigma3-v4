<?php

namespace App\Services\Ekstrakurikuler;

use App\Models\AnggotaEkskul;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Validation\ValidationException;

class RegisterEkskulService
{
    /**
     * @param Siswa $siswa
     * @param int $ekskulId
     * @throws ValidationException
     */
    public function execute(Siswa $siswa, int $ekskulId): void
    {
        // 1. Dapatkan tahun ajaran yang sedang aktif
        $tahunAktif = TahunAjaran::where('aktif', true)->first();

        if (!$tahunAktif) {
            throw ValidationException::withMessages([
                'ekskul' => 'Tidak ada tahun ajaran aktif yang ditemukan.',
            ]);
        }

        // 2. Simpan pendaftaran dengan status default 'aktif'
        // Karena ada unique constraint di migrasi, database akan menolak
        // jika siswa sudah terdaftar di ekskul yang sama pada tahun ini.
        try {
            AnggotaEkskul::create([
                'siswa_id' => $siswa->id,
                'ekstrakurikuler_id' => $ekskulId,
                'tahun_ajaran_id' => $tahunAktif->id,
                'tanggal_daftar' => now(),
                'status' => 'aktif',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek apakah error disebabkan oleh unique constraint (duplikasi)
            if ($e->errorInfo[1] == 1062 || str_contains($e->getMessage(), 'unique')) {
                throw ValidationException::withMessages([
                    'ekskul' => 'Anda sudah terdaftar di ekstrakurikuler ini.',
                ]);
            }

            throw $e;
        }
    }
}
