<?php

namespace App\Filament\Resources\KelasSiswas\Pages;

use App\Filament\Resources\KelasSiswas\KelasSiswaResource;
use App\Models\KelasSiswa;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateKelasSiswa extends CreateRecord
{
    protected static string $resource = KelasSiswaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = KelasSiswa::query()
            ->where('siswa_id', $data['siswa_id'])
            ->where('tahun_ajaran_id', $data['tahun_ajaran_id'])
            ->exists();

        if ($exists) {

            Notification::make()
                ->title('Siswa sudah terdaftar pada tahun ajaran tersebut.')
                ->danger()
                ->send();

            $this->halt();
        }

        return $data;
    }
}
