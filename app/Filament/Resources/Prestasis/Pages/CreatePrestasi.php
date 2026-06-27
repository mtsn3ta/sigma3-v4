<?php

namespace App\Filament\Resources\Prestasis\Pages;

use App\Filament\Resources\Prestasis\PrestasiResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePrestasi extends CreateRecord
{
    protected static string $resource = PrestasiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_id'] = Auth::id();

        return $data;
    }
}
