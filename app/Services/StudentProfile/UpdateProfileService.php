<?php

namespace App\Services\StudentProfile;

use App\Models\Siswa;
use App\Services\ActivityLogService;

class UpdateProfileService
{
    public function execute(Siswa $siswa, array $data): void
    {
        // Mendefinisikan kolom mana saja yang boleh diupdate melalui service ini
        // Ini melindungi dari input berbahaya (security best practice)
        $allowedFields = [
            'alamat',
            'no_hp',
            'alamat_ortu',
            'telpon_ortu',
            'nama_wali',
            'pekerjaan_wali',
            'alamat_wali',
            'telpon_wali',
            'nama_ayah',
            'nama_ibu',
            'pekerjaan_ayah',
            'pekerjaan_ibu',
            'nik_ayah',
            'nik_ibu',
            'anak_ke',
            'sekolah_asal'
        ];

        // Ambil hanya field yang ada di daftar $allowedFields
        $updateData = array_intersect_key($data, array_flip($allowedFields));

        // Update model
        $siswa->update($updateData);

        ActivityLogService::log(
            'Update Biodata Siswa',
            sprintf('%s memperbarui biodata.', $siswa->nama)
        );
    }
}
