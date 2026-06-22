<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Resources\Pages\EditRecord;

class EditSiswa extends EditRecord
{
    protected static string $resource = SiswaResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $siswa = $this->record;

        if ($siswa->user) {

            $siswa->user->update([
                'username' => $data['nisn'],
                'status' => $data['status'] === 'aktif'
                    ? 'aktif'
                    : 'nonaktif',
            ]);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
