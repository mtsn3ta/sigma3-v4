<?php

namespace App\Filament\Resources\KehadiranEkskuls\Pages;

use App\Filament\Resources\KehadiranEkskuls\KehadiranEkskulResource;
use Filament\Resources\Pages\ListRecords;

class ListKehadiranEkskuls extends ListRecords
{
    protected static string $resource = KehadiranEkskulResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
