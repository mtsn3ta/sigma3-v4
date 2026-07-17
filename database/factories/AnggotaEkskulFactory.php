<?php

namespace Database\Factories;

use App\Models\Ekstrakurikuler;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnggotaEkskulFactory extends Factory
{
    public function definition(): array
    {
        return [
            'siswa_id' => Siswa::factory(),
            'ekstrakurikuler_id' => Ekstrakurikuler::factory(),
            'tahun_ajaran_id' => TahunAjaran::factory(),

            'tanggal_daftar' => fake()->date(),

            'status' => fake()->randomElement([
                'aktif',
                'nonaktif',
            ]),
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn() => [
            'status' => 'aktif',
        ]);
    }

    public function nonaktif(): static
    {
        return $this->state(fn() => [
            'status' => 'nonaktif',
        ]);
    }
}
