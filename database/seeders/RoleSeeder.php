<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin',   'display_name' => 'Administrador', 'description' => 'Acceso total al sistema'],
            ['name' => 'trainer', 'display_name' => 'Entrenador',    'description' => 'Gestión de alumnos y rutinas'],
            ['name' => 'student', 'display_name' => 'Alumno',        'description' => 'Acceso al dashboard personal'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
