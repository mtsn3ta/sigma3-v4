<?php

namespace App\Filament\Resources\Galeris\Pages;

use App\Filament\Resources\Galeris\GaleriResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Services\ActivityLogService;

class EditGaleri extends EditRecord
{
    protected static string $resource = GaleriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        ActivityLogService::log(
            'Update Galeri',
            $this->record->judul
        );
    }
}
