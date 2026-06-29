<?php

namespace App\Filament\Resources\EkstrakurikulerPengampus\Pages;

use App\Filament\Resources\EkstrakurikulerPengampus\EkstrakurikulerPengampuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEkstrakurikulerPengampus extends ListRecords
{
    protected static string $resource = EkstrakurikulerPengampuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Pengampu'),
        ];
    }
}
