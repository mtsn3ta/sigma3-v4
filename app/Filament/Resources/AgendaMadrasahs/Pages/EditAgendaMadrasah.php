<?php

namespace App\Filament\Resources\AgendaMadrasahs\Pages;

use App\Filament\Resources\AgendaMadrasahs\AgendaMadrasahResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAgendaMadrasah extends EditRecord
{
    protected static string $resource = AgendaMadrasahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['tanggal_selesai'])) {
            $data['tanggal_selesai'] = $data['tanggal_mulai'] ?? $data['tanggal'];
        }
        if (!empty($data['tanggal_mulai'])) {
            $data['tanggal'] = $data['tanggal_mulai'];
        }

        return $data;
    }
}
