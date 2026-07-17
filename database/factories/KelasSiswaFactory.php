<?php

namespace Database\Factories;

use App\Models\KelasSiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KelasSiswa>
 */
class KelasSiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => 'aktif',
        ];
    }
}
