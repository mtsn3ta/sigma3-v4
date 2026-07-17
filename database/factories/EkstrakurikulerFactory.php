<?php

namespace Database\Factories;

use App\Models\Ekstrakurikuler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ekstrakurikuler>
 */
class EkstrakurikulerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->word(),
            'deskripsi' => fake()->sentence(),
            'jadwal' => fake()->dayOfWeek(),
            'kuota' => fake()->numberBetween(20, 100),
            'status' => 'aktif',
        ];
    }
}
