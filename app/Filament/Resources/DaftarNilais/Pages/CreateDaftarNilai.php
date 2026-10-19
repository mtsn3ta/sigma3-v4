<?php

namespace App\Filament\Resources\DaftarNilais\Pages;

use App\Filament\Resources\DaftarNilais\DaftarNilaiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDaftarNilai extends CreateRecord
{
    protected static string $resource = DaftarNilaiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->pegawai) {
            $data['pegawai_id'] = auth()->user()->pegawai->id;
        }

        return $data;
    }
}
