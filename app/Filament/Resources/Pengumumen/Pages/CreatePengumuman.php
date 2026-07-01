<?php

namespace App\Filament\Resources\Pengumumen\Pages;

use App\Filament\Resources\Pengumumen\PengumumanResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;

class CreatePengumuman extends CreateRecord
{
    protected static string $resource = PengumumanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_id'] = Auth::id();

        return $data;
    }
    protected function afterCreate(): void
    {
        ActivityLogService::log(
            'Create Pengumuman',
            $this->record->judul
        );
    }
    public function getTitle(): string
    {
        return 'Tambah Pengumuman';
    }
}
