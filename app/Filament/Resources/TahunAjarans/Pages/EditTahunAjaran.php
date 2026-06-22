<?php

namespace App\Filament\Resources\TahunAjarans\Pages;

use App\Filament\Resources\TahunAjarans\TahunAjaranResource;
use App\Models\TahunAjaran;
use Filament\Resources\Pages\EditRecord;

class EditTahunAjaran extends EditRecord
{
    protected static string $resource = TahunAjaranResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['aktif']) {

            TahunAjaran::query()
                ->where('id', '!=', $this->record->id)
                ->update([
                    'aktif' => false,
                ]);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
