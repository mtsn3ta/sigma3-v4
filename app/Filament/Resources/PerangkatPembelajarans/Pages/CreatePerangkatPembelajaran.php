<?php

namespace App\Filament\Resources\PerangkatPembelajarans\Pages;

use App\Filament\Resources\PerangkatPembelajarans\PerangkatPembelajaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePerangkatPembelajaran extends CreateRecord
{
    protected static string $resource = PerangkatPembelajaranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->pegawai) {
            $data['pegawai_id'] = auth()->user()->pegawai->id;
        }

        return $data;
    }
}
