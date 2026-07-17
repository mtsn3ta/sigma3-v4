<?php

namespace App\Filament\Resources\KelasSiswas\Pages;

use App\Filament\Resources\KelasSiswas\KelasSiswaResource;
use Filament\Resources\Pages\EditRecord;

class EditKelasSiswa extends EditRecord
{
    protected static string $resource = KelasSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
