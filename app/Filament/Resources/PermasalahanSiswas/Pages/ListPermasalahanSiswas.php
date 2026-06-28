<?php

namespace App\Filament\Resources\PermasalahanSiswas\Pages;

use App\Filament\Resources\PermasalahanSiswas\PermasalahanSiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPermasalahanSiswas extends ListRecords
{
    protected static string $resource = PermasalahanSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
