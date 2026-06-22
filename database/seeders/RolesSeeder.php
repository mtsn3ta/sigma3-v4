<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [

            'super_admin',

            'kepala_madrasah',

            'guru',

            'waka_kesiswaan',
            'waka_kurikulum',
            'waka_humas',
            'waka_sarpras',

            'bk',
            'piket',

            'pembina',
            'pelatih',

            'siswa',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
    }
}
