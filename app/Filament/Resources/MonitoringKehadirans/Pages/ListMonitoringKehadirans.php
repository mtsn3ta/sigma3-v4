<?php

namespace App\Filament\Resources\MonitoringKehadirans\Pages;

use App\Filament\Resources\MonitoringKehadirans\MonitoringKehadiranResource;
use Filament\Resources\Pages\ListRecords;

class ListMonitoringKehadirans extends ListRecords
{
    protected static string $resource = MonitoringKehadiranResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
