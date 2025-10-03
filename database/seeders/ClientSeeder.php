<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'cli_first_name' => 'Juan',
                'cli_middle_name' => 'Carlos',
                'cli_last_name' => 'Pérez',
                'cli_second_last_name' => 'Gómez',
                'cli_document_type' => 'CC',
                'cli_document_number' => '12345678',
                'cli_email' => 'juan.perez@email.com',
                'cli_phone' => '3001234567',
                'cli_address' => 'Calle 123 #45-67, Bogotá'
            ],
            [
                'cli_first_name' => 'María',
                'cli_middle_name' => 'Elena',
                'cli_last_name' => 'García',
                'cli_second_last_name' => 'López',
                'cli_document_type' => 'CC',
                'cli_document_number' => '87654321',
                'cli_email' => 'maria.garcia@email.com',
                'cli_phone' => '3009876543',
                'cli_address' => 'Carrera 45 #67-89, Medellín'
            ],
            [
                'cli_first_name' => 'Carlos',
                'cli_middle_name' => null,
                'cli_last_name' => 'Rodríguez',
                'cli_second_last_name' => 'Martínez',
                'cli_document_type' => 'CE',
                'cli_document_number' => '98765432',
                'cli_email' => 'carlos.rodriguez@email.com',
                'cli_phone' => '3112345678',
                'cli_address' => 'Avenida 80 #123-45, Cali'
            ],
            [
                'cli_first_name' => 'Ana',
                'cli_middle_name' => 'Sofía',
                'cli_last_name' => 'Hernández',
                'cli_second_last_name' => null,
                'cli_document_type' => 'CC',
                'cli_document_number' => '56789012',
                'cli_email' => 'ana.hernandez@email.com',
                'cli_phone' => '3158765432',
                'cli_address' => null
            ],
            [
                'cli_first_name' => 'Luis',
                'cli_middle_name' => 'Fernando',
                'cli_last_name' => 'Torres',
                'cli_second_last_name' => 'Vargas',
                'cli_document_type' => 'TI',
                'cli_document_number' => '34567890',
                'cli_email' => 'luis.torres@email.com',
                'cli_phone' => '3201234567',
                'cli_address' => 'Transversal 12 #34-56, Barranquilla'
            ],
            [
                'cli_first_name' => 'Empresa',
                'cli_middle_name' => null,
                'cli_last_name' => 'Tecnología',
                'cli_second_last_name' => 'SAS',
                'cli_document_type' => 'NIT',
                'cli_document_number' => '900123456-1',
                'cli_email' => 'contacto@empresa.com',
                'cli_phone' => '6012345678',
                'cli_address' => 'Zona Industrial, Bogotá'
            ]
        ];

        foreach ($clients as $clientData) {
            Client::firstOrCreate(
                ['cli_document_number' => $clientData['cli_document_number']],
                $clientData
            );
        }
    }
}