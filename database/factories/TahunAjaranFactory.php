<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TahunAjaranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tahun' => '2026/2027',
            'aktif' => false,
        ];
    }
}
