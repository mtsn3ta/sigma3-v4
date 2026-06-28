<?php

namespace App\Filament\Resources\Pegawais\Pages;

use App\Filament\Resources\Pegawais\PegawaiResource;
use App\Models\User;
use App\Support\RoleHelper;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'username'             => $data['nik_nip'],
            'email'                => null,
            'password'             => Hash::make('mtsn3ta123'),
            'must_change_password' => true,
            'status'               => $data['status'],
        ]);

        RoleHelper::syncPegawaiRoles(
            $user,
            $data['jenis_pegawai'],
            $data['roles'] ?? [],
        );

        unset($data['roles']);

        $data['user_id'] = $user->id;

        return $data;
    }
}
