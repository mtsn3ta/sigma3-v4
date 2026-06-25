<?php

namespace App\Filament\Resources\EkstrakurikulerPengampus\Pages;

use App\Filament\Resources\EkstrakurikulerPengampus\EkstrakurikulerPengampuResource;
use App\Models\EkstrakurikulerPengampu;
use App\Models\Pegawai;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEkstrakurikulerPengampu extends EditRecord
{
    protected static string $resource = EkstrakurikulerPengampuResource::class;

    protected function getHeaderActions(): array
    {
        return [

            DeleteAction::make()

                ->after(function () {

                    $pegawai = Pegawai::find($this->record->pegawai_id);

                    if (! $pegawai?->user) {
                        return;
                    }

                    $user = $pegawai->user;

                    $masihPembina = EkstrakurikulerPengampu::query()
                        ->where('pegawai_id', $pegawai->id)
                        ->where('role', 'pembina')
                        ->exists();

                    if (! $masihPembina) {
                        $user->removeRole('pembina');
                    }

                    $masihPelatih = EkstrakurikulerPengampu::query()
                        ->where('pegawai_id', $pegawai->id)
                        ->where('role', 'pelatih')
                        ->exists();

                    if (! $masihPelatih) {
                        $user->removeRole('pelatih');
                    }
                }),

        ];
    }
}
