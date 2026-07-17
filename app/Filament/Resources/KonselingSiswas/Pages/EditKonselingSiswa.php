<?php

namespace App\Filament\Resources\KonselingSiswas\Pages;

use App\Filament\Resources\KonselingSiswas\KonselingSiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKonselingSiswa extends EditRecord
{
    protected static string $resource = KonselingSiswaResource::class;
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
