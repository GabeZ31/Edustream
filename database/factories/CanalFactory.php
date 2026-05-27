<?php

namespace Database\Factories;

use App\Models\Canal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Canal>
 */
class CanalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => 'Canal de ' . fake()->word(),
            'descripcion' => fake()->sentence(8),
            'maestro_id' => \App\Models\User::factory()->maestro(),
        ];
    }
}
