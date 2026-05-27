<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear el usuario Administrador fijo (Tus credenciales originales)
        $admin = User::firstOrCreate(
            ['email' => 'ladypure31@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password123'),
                'rol' => 'admin'
            ]
        );

        // 2. Crear 3 Maestros (1 fijo y 2 aleatorios)
        $maestros = collect();
        $maestros->push(User::firstOrCreate(
            ['email' => 'maestro1@edustream.com'],
            [
                'name' => 'Maestro Principal',
                'password' => bcrypt('password123'),
                'rol' => 'maestro'
            ]
        ));
        $maestros = $maestros->concat(User::factory(2)->maestro()->create());

        // Por cada maestro, crear 2 canales
        foreach ($maestros as $maestro) {
            $canales = \App\Models\Canal::factory(2)->create([
                'maestro_id' => $maestro->id
            ]);

            // Por cada canal, crear 3 recursos
            foreach ($canales as $canal) {
                \App\Models\Recurso::factory(3)->create([
                    'canal_id' => $canal->id
                ]);
            }
        }

        // 3. Crear 3 Estudiantes (1 fijo y 2 aleatorios)
        User::firstOrCreate(
            ['email' => 'alumno1@edustream.com'],
            [
                'name' => 'Estudiante Uno',
                'password' => bcrypt('password123'),
                'rol' => 'estudiante'
            ]
        );
        User::factory(2)->estudiante()->create();
    }
}
