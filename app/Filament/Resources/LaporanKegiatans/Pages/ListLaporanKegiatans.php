<?php

namespace App\Filament\Resources\LaporanKegiatans\Pages;

use App\Filament\Resources\LaporanKegiatans\LaporanKegiatanResource;
use Filament\Resources\Pages\ListRecords;

class ListLaporanKegiatans extends ListRecords
{
    protected static string $resource =
    LaporanKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
