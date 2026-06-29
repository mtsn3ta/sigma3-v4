<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use App\Services\ActivityLogService;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'username' => $data['nisn'],
            'password' => Hash::make('mtsn3ta123'),
            'must_change_password' => true,
            'status' => 'aktif',
        ]);

        $user->assignRole('siswa');

        $data['user_id'] = $user->id;

        return $data;
    }
    protected function afterCreate(): void
    {
        ActivityLogService::log(
            'Create Siswa',
            'Menambahkan siswa ' . $this->record->nama
        );
    }
}
