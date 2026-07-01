<?php

namespace App\Filament\Resources\KonselingSiswas\Pages;

use App\Filament\Resources\KonselingSiswas\KonselingSiswaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateKonselingSiswa extends CreateRecord
{
    protected static string $resource = KonselingSiswaResource::class;

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
        return 'Tambah Konseling Siswa';
    }
}
