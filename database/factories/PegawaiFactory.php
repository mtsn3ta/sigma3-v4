<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PegawaiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nik_nip' => fake()->unique()->numerify('##################'),
            'jenis_pegawai' => 'internal',
            'no_hp' => fake()->phoneNumber(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date(),
            'alamat' => fake()->address(),
            'status' => 'aktif',
        ];
    }
}
