<?php

namespace App\Filament\Resources\IzinKeluars\Pages;

use App\Filament\Resources\IzinKeluars\IzinKeluarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIzinKeluar extends EditRecord
{
    protected static string $resource = IzinKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
