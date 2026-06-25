<?php

namespace App\Filament\Resources\ProposalKegiatans\Pages;

use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProposalKegiatans extends ListRecords
{
    protected static string $resource = ProposalKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
