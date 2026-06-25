<?php

namespace App\Filament\Resources\EkstrakurikulerPengampus\Pages;

use App\Filament\Resources\EkstrakurikulerPengampus\EkstrakurikulerPengampuResource;
use App\Models\Pegawai;
use Filament\Resources\Pages\CreateRecord;

class CreateEkstrakurikulerPengampu extends CreateRecord
{
    protected static string $resource = EkstrakurikulerPengampuResource::class;

    protected function afterCreate(): void
    {
        $pegawai = Pegawai::find($this->record->pegawai_id);

        if (! $pegawai?->user) {
            return;
        }

        if ($this->record->role === 'pembina') {
            $pegawai->user->assignRole('pembina');
        }

        if ($this->record->role === 'pelatih') {
            $pegawai->user->assignRole('pelatih');
        }
    }
}
