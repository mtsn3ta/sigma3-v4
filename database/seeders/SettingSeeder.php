<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(

            ['id' => 1],

            [

                'nama_madrasah' => 'MTsN 3 Tulungagung',

                'alamat' => '',

                'telepon' => '',

                'email' => '',

                'logo' => null,

                'favicon' => null,

                'kepala_aktif_id' => null,

                'tahun_ajaran_aktif_id' => null,

            ]

        );
    }
}
