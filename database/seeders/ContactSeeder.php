<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use Illuminate\Support\Carbon;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'business_id' => 1,
                'client_name' => 'Lucas Romero',
                'email' => 'lucas.romero@email.com',
                'client_phone' => '+34 612 345 678',
                'message' => 'Estoy interesado en conocer más sobre sus servicios.',
                'contacted_at' => Carbon::parse('2025-03-20 10:00:00'),
            ],
            [
                'business_id' => 1,
                'client_name' => 'Elena Martínez',
                'email' => 'elena.martinez@email.com',
                'client_phone' => '+34 611 111 222',
                'message' => '¿Tienen algún descuento para nuevos clientes?',
                'contacted_at' => Carbon::parse('2025-03-18 15:45:00'),
            ],
            [
                'business_id' => 1,
                'client_name' => 'David Torres',
                'email' => 'david.torres@email.com',
                'client_phone' => '+34 699 999 999',
                'message' => 'Me gustaría recibir más información sobre horarios.',
                'contacted_at' => Carbon::parse('2025-03-10 09:15:00'),
            ],
            [
                'business_id' => 1,
                'client_name' => 'Carla Gómez',
                'email' => 'carla.gomez@email.com',
                'client_phone' => '+34 655 123 456',
                'message' => '¿Tienen disponibilidad para esta semana?',
                'contacted_at' => Carbon::parse('2025-03-08 12:30:00'),
            ],
            [
                'business_id' => 1,
                'client_name' => 'Sofía Pérez',
                'email' => 'sofia.perez@email.com',
                'client_phone' => '+34 633 333 333',
                'message' => 'Estoy evaluando contratar su servicio para mi empresa.',
                'contacted_at' => Carbon::parse('2025-02-28 18:00:00'),
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}
