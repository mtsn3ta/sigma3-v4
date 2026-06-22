<?php

namespace App\Filament\Resources\Pegawais\Pages;

use App\Filament\Resources\Pegawais\PegawaiResource;
use Filament\Resources\Pages\EditRecord;

class EditPegawai extends EditRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $pegawai = $this->record;

        if ($pegawai->user) {
            $pegawai->user->update([
                'username' => $data['nik_nip'],
                'status' => $data['status'],
            ]);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
