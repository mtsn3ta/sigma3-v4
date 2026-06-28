<?php

namespace App\Filament\Resources\Pegawais\Pages;

use App\Filament\Resources\Pegawais\PegawaiResource;
use App\Support\RoleHelper;
use Filament\Resources\Pages\EditRecord;

class EditPegawai extends EditRecord
{
    protected static string $resource = PegawaiResource::class;

    /**
     * Isi checkbox role saat membuka form edit.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['roles'] = $this->record
            ->user
            ->roles
            ->pluck('name')
            ->filter(fn($role) => in_array($role, [

                'kepala_madrasah',

                'waka_kesiswaan',
                'waka_kurikulum',
                'waka_humas',
                'waka_sarpras',

                'bk',
                'piket',

            ]))
            ->values()
            ->toArray();

        return $data;
    }

    /**
     * Update user dan sinkronkan role.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $pegawai = $this->record;

        if ($pegawai->user) {

            $pegawai->user->update([
                'username' => $data['nik_nip'],
                'status'   => $data['status'],
            ]);

            RoleHelper::syncPegawaiRoles(
                $pegawai->user,
                $data['jenis_pegawai'],
                $data['roles'] ?? [],
            );
        }

        unset($data['roles']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
