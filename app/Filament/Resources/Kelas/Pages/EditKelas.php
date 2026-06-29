<?php

namespace App\Filament\Resources\Kelas\Pages;

use App\Filament\Resources\Kelas\KelasResource;
use Filament\Resources\Pages\EditRecord;
use App\Services\ActivityLogService;

class EditKelas extends EditRecord
{
    protected static string $resource = KelasResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
    protected function afterSave(): void
    {
        ActivityLogService::log(
            'Update Kelas',
            $this->record->nama_kelas
        );
    }
}
