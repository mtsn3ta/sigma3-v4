<?php

namespace App\Filament\Resources\EkstrakurikulerPengampus\Pages;

use App\Filament\Resources\EkstrakurikulerPengampus\EkstrakurikulerPengampuResource;
use Filament\Resources\Pages\EditRecord;

class EditEkstrakurikulerPengampu extends EditRecord
{
    protected static string $resource = EkstrakurikulerPengampuResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
