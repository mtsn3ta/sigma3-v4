<?php

namespace App\Filament\Resources\PermasalahanSiswas\Pages;

use App\Filament\Resources\PermasalahanSiswas\PermasalahanSiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPermasalahanSiswa extends EditRecord
{
    protected static string $resource = PermasalahanSiswaResource::class;
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        // Pastikan kita ambil kelas dari siswa
        if ($record->siswa) {
            $data['kelas_id'] = $record->siswa->kelasSiswa()->latest()->first()?->kelas_id;
        }

        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
