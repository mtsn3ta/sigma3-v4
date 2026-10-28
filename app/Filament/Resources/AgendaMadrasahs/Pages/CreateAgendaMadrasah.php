<?php

namespace App\Filament\Resources\AgendaMadrasahs\Pages;

use App\Filament\Resources\AgendaMadrasahs\AgendaMadrasahResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAgendaMadrasah extends CreateRecord
{
    protected static string $resource = AgendaMadrasahResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['tanggal_selesai'])) {
            $data['tanggal_selesai'] = $data['tanggal_mulai'];
        }
        $data['tanggal'] = $data['tanggal_mulai'];

        return $data;
    }
}
