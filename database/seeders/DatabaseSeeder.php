<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Business; // Agregalo arriba
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;




class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear un usuario de prueba
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Crear un negocio de prueba con ID 1
        $business = Business::create([
            'user_id' => $user->id,
            'name' => 'Negocio de Prueba',
            'email' => 'negocio@prueba.com',
            'phone' => '+34 600 123 456',
            'category' => 'Restaurante',
            'location' => 'Madrid, España',
            'description' => 'Este es un negocio ficticio para pruebas.',
        ]);

        // Llamar a los seeders (ahora que existe un negocio)
        $this->call([
            ReviewSeeder::class,
            ContactSeeder::class,
        ]);
    }
}

