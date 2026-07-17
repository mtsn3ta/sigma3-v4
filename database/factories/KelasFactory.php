<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KelasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_kelas' => '7A',
            'tingkat' => 7,
            'status' => 'aktif',
        ];
    }
}
