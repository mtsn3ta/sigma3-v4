<?php

namespace Database\Factories;

use App\Models\SesiKegiatan;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class KehadiranEkskulFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sesi_kegiatan_id' => SesiKegiatan::factory(),
            'siswa_id' => Siswa::factory(),

            'status' => fake()->randomElement([
                'hadir',
                'izin',
                'sakit',
                'alpa',
            ]),

            'sumber' => fake()->randomElement([
                'manual',
                'rfid',
            ]),

            'petugas_id' => null,

            'waktu_scan' => now(),
        ];
    }
}
