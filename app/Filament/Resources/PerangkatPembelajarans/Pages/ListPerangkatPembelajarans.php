<?php

namespace App\Filament\Resources\PerangkatPembelajarans\Pages;

use App\Filament\Resources\PerangkatPembelajarans\PerangkatPembelajaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPerangkatPembelajarans extends ListRecords
{
    protected static string $resource = PerangkatPembelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
