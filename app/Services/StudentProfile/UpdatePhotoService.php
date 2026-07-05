<?php

namespace App\Services\StudentProfile;

use App\Models\Siswa;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdatePhotoService
{
    /**
     * @param Siswa $siswa
     * @param string|TemporaryUploadedFile|null $photo
     */
    public function execute(Siswa $siswa, $photo): void
    {
        // 1. Jika photo kosong, tidak ada yang perlu dilakukan
        if (blank($photo)) {
            return;
        }

        // 2. Jika $photo adalah string, berarti itu path yang sudah ada di database.
        // Jika nilainya sama dengan yang di database, abaikan.
        if (is_string($photo) && $photo === $siswa->foto) {
            return;
        }

        // 3. Jika $photo adalah objek (TemporaryUploadedFile), proses penggantian
        if ($photo instanceof TemporaryUploadedFile) {

            // Hapus foto lama jika ada
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            // Simpan foto baru
            $path = $photo->store('siswa', 'public');

            // Update model
            $siswa->update(['foto' => $path]);

            // Log aktivitas
            ActivityLogService::log(
                'Update Foto Profil',
                sprintf('%s memperbarui foto profil.', $siswa->nama)
            );
        }
    }
}
