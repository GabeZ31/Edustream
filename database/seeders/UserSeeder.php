<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrador
        User::updateOrCreate(
            ['email' => 'admin@edustream.com'],
            [
                'name' => 'Administrador',
                'password' => 'admin1',
                'rol' => 'admin'
            ]
        );

        // Maestros 
        for ($i = 1; $i <= 3; $i++) {
            User::updateOrCreate(
                ['email' => "maestro{$i}@edustream.com"],
                [
                    'name' => "Maestro {$i}",
                    'password' => "maestro{$i}",
                    'rol' => 'maestro'
                ]
            );
        }

        // Estudiantes
        for ($i = 1; $i <= 3; $i++) {
            User::updateOrCreate(
                ['email' => "alumno{$i}@edustream.com"],
                [
                    'name' => "Alumno {$i}",
                    'password' => "alumno{$i}",
                    'rol' => 'estudiante'
                ]
            );
        }
    }
}
