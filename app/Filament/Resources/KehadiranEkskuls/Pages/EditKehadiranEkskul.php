<?php

namespace App\Filament\Resources\KehadiranEkskuls\Pages;

use App\Filament\Resources\KehadiranEkskuls\KehadiranEkskulResource;
use Filament\Resources\Pages\EditRecord;

class EditKehadiranEkskul extends EditRecord
{
    protected static string $resource = KehadiranEkskulResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
