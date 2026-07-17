<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            [
                'username' => 'superadmin',
            ],
            [
                'email' => null,
                'password' => Hash::make('mtsn3ta123'),
                'must_change_password' => false,
                'status' => 'aktif',
            ]
        );

        if (! $user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
        }
    }
}
