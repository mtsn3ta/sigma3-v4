<?php

namespace App\Filament\Resources\AnggotaEkskuls\Pages;

use App\Filament\Resources\AnggotaEkskuls\AnggotaEkskulResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAnggotaEkskul extends EditRecord
{
    protected static string $resource = AnggotaEkskulResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
