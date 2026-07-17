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

    // Mencegah error kolom tidak ditemukan saat Waka Kurikulum menyimpan hasil edit
    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['tanggal_selesai']);
        return $data;
    }
}
