<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Resources\Pages\EditRecord;
use App\Services\ActivityLogService;

class EditSiswa extends EditRecord
{
    protected static string $resource = SiswaResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $siswa = $this->record;

        if ($siswa->user) {
            
            // Langsung ambil nilai status dari form. 
            // Jika tidak ada (null/undefined), gunakan status user yang lama.
            $statusUser = $data['status'] ?? $siswa->user->status;

            // Lakukan hal yang sama untuk 'nisn'
            $username = $data['nisn'] ?? $siswa->user->username;

            $siswa->user->update([
                'username' => $username,
                'status' => $statusUser,
            ]);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
    protected function afterSave(): void
    {
        ActivityLogService::log(
            'Update Siswa',
            'Mengubah data siswa ' . $this->record->nama
        );
    }
}
