<?php

namespace App\Filament\Resources\TugasGuruIzins\Pages;

use App\Filament\Resources\TugasGuruIzins\TugasGuruIzinResource;
use Filament\Resources\Pages\ListRecords;

class ListTugasGuruIzins extends ListRecords
{
    protected static string $resource = TugasGuruIzinResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
