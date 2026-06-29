<?php

namespace App\Filament\Resources\Ekstrakurikulers\Pages;

use App\Filament\Resources\Ekstrakurikulers\EkstrakurikulerResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\ActivityLogService;

class CreateEkstrakurikuler extends CreateRecord
{
    protected static string $resource = EkstrakurikulerResource::class;
    protected function afterCreate(): void
    {
        ActivityLogService::log(
            'Create Ekstrakurikuler',
            $this->record->nama
        );
    }
}
