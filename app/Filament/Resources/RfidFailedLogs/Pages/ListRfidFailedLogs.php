<?php

namespace App\Filament\Resources\RfidFailedLogs\Pages;

use App\Filament\Resources\RfidFailedLogs\RfidFailedLogResource;
use Filament\Resources\Pages\ListRecords;

class ListRfidFailedLogs extends ListRecords
{
    protected static string $resource = RfidFailedLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
