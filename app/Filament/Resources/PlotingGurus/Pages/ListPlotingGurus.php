<?php

namespace App\Filament\Resources\PlotingGurus\Pages;

use App\Filament\Resources\PlotingGurus\PlotingGuruResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlotingGurus extends ListRecords
{
    protected static string $resource = PlotingGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
