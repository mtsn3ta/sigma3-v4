<?php

namespace App\Filament\Resources\KonselingSiswas\Pages;

use App\Filament\Resources\KonselingSiswas\KonselingSiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKonselingSiswas extends ListRecords
{
    protected static string $resource = KonselingSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Konseling'),
        ];
    }
    public function getTitle(): string
    {
        return 'Daftar Konseling Siswa';
    }
}
