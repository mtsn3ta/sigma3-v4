<?php

namespace App\Filament\Resources\PermasalahanSiswas\Pages;

use App\Filament\Resources\PermasalahanSiswas\PermasalahanSiswaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePermasalahanSiswa extends CreateRecord
{
    protected static string $resource = PermasalahanSiswaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        abort_if(
            ! Auth::user()?->pegawai,
            403,
            'Data pegawai tidak ditemukan.'
        );

        $data['pegawai_id'] = Auth::user()->pegawai->id;

        return $data;
    }
    public function getTitle(): string
    {
        return 'Tambah Permasalahan Siswa';
    }
}
