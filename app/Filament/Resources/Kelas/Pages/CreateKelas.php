<?php

namespace App\Filament\Resources\Kelas\Pages;

use App\Filament\Resources\Kelas\KelasResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\ActivityLogService;

class CreateKelas extends CreateRecord
{
    protected static string $resource = KelasResource::class;
    protected function afterCreate(): void
    {
        ActivityLogService::log(
            'Create Kelas',
            $this->record->nama_kelas
        );
    }
}
