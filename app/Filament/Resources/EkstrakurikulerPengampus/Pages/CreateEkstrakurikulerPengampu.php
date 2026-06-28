<?php

namespace App\Filament\Resources\EkstrakurikulerPengampus\Pages;

use App\Filament\Resources\EkstrakurikulerPengampus\EkstrakurikulerPengampuResource;
use App\Models\EkstrakurikulerPengampu;
use App\Models\Pegawai;
use App\Support\RoleHelper;
use Filament\Resources\Pages\CreateRecord;

class CreateEkstrakurikulerPengampu extends CreateRecord
{
    protected static string $resource = EkstrakurikulerPengampuResource::class;

    protected function handleRecordCreation(array $data): EkstrakurikulerPengampu
    {
        $pegawai = Pegawai::findOrFail($data['pegawai_id']);

        $firstRecord = null;

        foreach ($data['roles'] as $role) {

            $record = EkstrakurikulerPengampu::firstOrCreate([

                'ekstrakurikuler_id' => $data['ekstrakurikuler_id'],

                'pegawai_id'         => $data['pegawai_id'],

                'role'               => $role,

            ]);

            $firstRecord ??= $record;

            if ($record->wasRecentlyCreated && $pegawai->user) {

                RoleHelper::assignPengampuRole(
                    $pegawai->user,
                    $role
                );
            }
        }

        return $firstRecord;
    }
}
