<?php

namespace App\Filament\Resources\IzinKeluars\Pages;

use App\Filament\Resources\IzinKeluars\IzinKeluarResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateIzinKeluar extends CreateRecord
{
    protected static string $resource = IzinKeluarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pegawai_id'] = Auth::user()->pegawai->id;

        $data['status_proses'] ??= 'keluar';

        $data['jam_keluar'] ??= now()->format('H:i:s');

        return $data;
    }
}
