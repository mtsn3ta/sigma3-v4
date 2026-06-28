<?php

namespace App\Filament\Resources\PermasalahanSiswas\Pages;

use App\Filament\Resources\PermasalahanSiswas\PermasalahanSiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPermasalahanSiswa extends EditRecord
{
    protected static string $resource = PermasalahanSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
