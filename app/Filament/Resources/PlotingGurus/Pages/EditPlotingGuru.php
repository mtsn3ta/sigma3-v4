<?php

namespace App\Filament\Resources\PlotingGurus\Pages;

use App\Filament\Resources\PlotingGurus\PlotingGuruResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlotingGuru extends EditRecord
{
    protected static string $resource = PlotingGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
