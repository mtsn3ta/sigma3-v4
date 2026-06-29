<?php

namespace App\Filament\Resources\TahunAjarans\Pages;

use App\Filament\Resources\TahunAjarans\TahunAjaranResource;
use App\Models\TahunAjaran;
use Filament\Resources\Pages\CreateRecord;
use App\Services\ActivityLogService;

class CreateTahunAjaran extends CreateRecord
{
    protected static string $resource = TahunAjaranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['aktif']) {

            TahunAjaran::query()
                ->update([
                    'aktif' => false,
                ]);
        }

        return $data;
    }
    protected function afterCreate(): void
    {
        ActivityLogService::log(
            'Create Tahun Ajaran',
            $this->record->tahun
        );
    }
}
