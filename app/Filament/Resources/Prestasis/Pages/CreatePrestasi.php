<?php

namespace App\Filament\Resources\Prestasis\Pages;

use App\Filament\Resources\Prestasis\PrestasiResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;

class CreatePrestasi extends CreateRecord
{
    protected static string $resource = PrestasiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_id'] = Auth::id();

        return $data;
    }
    protected function afterCreate(): void
    {
        ActivityLogService::log(
            'Create Prestasi',
            $this->record->judul
        );
    }
    public function getTitle(): string
    {
        return 'Tambah Prestasi';
    }
}
