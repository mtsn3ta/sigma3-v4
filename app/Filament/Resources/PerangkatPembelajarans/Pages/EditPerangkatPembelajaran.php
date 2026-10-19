<?php

namespace App\Filament\Resources\PerangkatPembelajarans\Pages;

use App\Filament\Resources\PerangkatPembelajarans\PerangkatPembelajaranResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPerangkatPembelajaran extends EditRecord
{
    protected static string $resource = PerangkatPembelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
