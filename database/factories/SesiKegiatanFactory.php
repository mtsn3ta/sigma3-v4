<?php

namespace Database\Factories;

use App\Models\Ekstrakurikuler;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class SesiKegiatanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ekstrakurikuler_id' => Ekstrakurikuler::factory(),
            'pelatih_id' => Pegawai::factory(),

            'tanggal' => fake()->date(),

            'waktu_mulai' => '15:00:00',
            'waktu_selesai' => '17:00:00',

            'status' => fake()->randomElement([
                'aktif',
                'selesai',
                'batal',
            ]),
        ];
    }
}
