<?php

namespace App\Filament\Resources\SesiKegiatans\Pages;

use App\Filament\Resources\SesiKegiatans\SesiKegiatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSesiKegiatans extends ListRecords
{
    protected static string $resource = SesiKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
