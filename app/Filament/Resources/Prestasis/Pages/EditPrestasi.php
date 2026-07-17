<?php

namespace App\Filament\Resources\Prestasis\Pages;

use App\Filament\Resources\Prestasis\PrestasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Services\ActivityLogService;

class EditPrestasi extends EditRecord
{
    protected static string $resource = PrestasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        ActivityLogService::log(
            'Update Prestasi',
            $this->record->judul
        );
    }
}
