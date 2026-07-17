<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    public function definition(): array
    {
        return [

            'nisn' => fake()->unique()->numerify('##########'),

            'nama' => fake()->name(),

            'jenis_kelamin' => fake()->randomElement([
                'L',
                'P',
            ]),

            'tempat_lahir' => fake()->city(),

            'tanggal_lahir' => fake()->date(),

            'alamat' => fake()->address(),

            'status' => 'aktif',
        ];
    }
}
