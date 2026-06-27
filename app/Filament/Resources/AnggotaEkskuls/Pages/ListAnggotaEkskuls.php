<?php

namespace App\Filament\Resources\AnggotaEkskuls\Pages;

use App\Filament\Resources\AnggotaEkskuls\AnggotaEkskulResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAnggotaEkskuls extends ListRecords
{
    protected static string $resource = AnggotaEkskulResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Anggota'),
        ];
    }
}
