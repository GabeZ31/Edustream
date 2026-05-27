<?php

namespace Database\Factories;

use App\Models\Recurso;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recurso>
 */
class RecursoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipos = ['video', 'pdf', 'documento', 'otro'];
        
        return [
            'titulo' => ucfirst(fake()->words(3, true)),
            'descripcion' => fake()->paragraph(),
            'tipo' => fake()->randomElement($tipos),
            'archivo' => 'recursos/dummy_file.pdf',
            'portada' => 'portada' . rand(1, 9) . '.png',
            'canal_id' => \App\Models\Canal::factory(),
        ];
    }
}
