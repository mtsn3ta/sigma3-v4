<?php

namespace App\Filament\Resources\DaftarNilais\Pages;

use App\Filament\Resources\DaftarNilais\DaftarNilaiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDaftarNilais extends ListRecords
{
    protected static string $resource = DaftarNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
