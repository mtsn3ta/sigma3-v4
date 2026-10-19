<?php

namespace App\Filament\Resources\DaftarNilais\Pages;

use App\Filament\Resources\DaftarNilais\DaftarNilaiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDaftarNilai extends EditRecord
{
    protected static string $resource = DaftarNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
