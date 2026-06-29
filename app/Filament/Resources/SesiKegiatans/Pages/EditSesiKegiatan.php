<?php

namespace App\Filament\Resources\SesiKegiatans\Pages;

use App\Filament\Resources\SesiKegiatans\SesiKegiatanResource;
use Filament\Resources\Pages\EditRecord;
use App\Services\ActivityLogService;

class EditSesiKegiatan extends EditRecord
{
    protected static string $resource = SesiKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
    protected function afterSave(): void
    {
        ActivityLogService::log(
            'Update Sesi',
            $this->record->ekstrakurikuler->nama
        );
    }
}
