<?php

namespace App\Filament\Resources\Ekstrakurikulers\Pages;

use App\Filament\Resources\Ekstrakurikulers\EkstrakurikulerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Services\ActivityLogService;

class EditEkstrakurikuler extends EditRecord
{
    protected static string $resource = EkstrakurikulerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        ActivityLogService::log(
            'Update Ekstrakurikuler',
            $this->record->nama
        );
    }
}
