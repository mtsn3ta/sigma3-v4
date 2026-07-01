<?php

namespace App\Filament\Resources\Galeris\Pages;

use App\Filament\Resources\Galeris\GaleriResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;

class CreateGaleri extends CreateRecord
{
    protected static string $resource = GaleriResource::class;

    protected array $uploadedPhotos = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->uploadedPhotos = $data['foto_baru'] ?? [];

        unset($data['foto_baru']);

        $data['created_by_id'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->uploadedPhotos as $index => $foto) {

            $this->record->foto()->create([

                'foto'    => $foto,

                'urutan'  => $index + 1,

            ]);
        }
        ActivityLogService::log(
            'Create Galeri',
            $this->record->judul
        );
    }
    public function getTitle(): string
    {
        return 'Tambah Galeri';
    }
}
