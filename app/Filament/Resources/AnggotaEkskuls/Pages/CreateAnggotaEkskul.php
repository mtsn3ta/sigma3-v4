<?php

namespace App\Filament\Resources\AnggotaEkskuls\Pages;

use App\Filament\Resources\AnggotaEkskuls\AnggotaEkskulResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnggotaEkskul extends CreateRecord
{
    protected static string $resource = AnggotaEkskulResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = \App\Models\AnggotaEkskul::query()
            ->where('siswa_id', $data['siswa_id'])
            ->where('ekstrakurikuler_id', $data['ekstrakurikuler_id'])
            ->where('tahun_ajaran_id', $data['tahun_ajaran_id'])
            ->exists();

        if ($exists) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'ekstrakurikuler_id' =>
                'Siswa sudah terdaftar pada ekstrakurikuler ini di tahun ajaran yang sama.',
            ]);
        }

        return $data;
    }
    public function getTitle(): string
    {
        return 'Tambah Anggota Ekstrakurikuler';
    }
}
