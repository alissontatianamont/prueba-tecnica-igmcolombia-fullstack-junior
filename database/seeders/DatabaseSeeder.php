<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "🚀 Iniciando configuración de la aplicación...\n\n";

        $this->setupRolesAndPermissions();
        
        $this->createDefaultUsers();
        
        $this->createSampleClients();
         
        $this->createSampleProducts();
        
        echo "Usuarios creados:\n";
        echo "   - Admin: admin@example.com (password: passwordadmin123)\n";
        echo "   - Vendedor: salesman@example.com (password: passwordsales123)\n";
    }

    private function setupRolesAndPermissions(): void
    {
        echo "1️⃣ Configurando roles y permisos...\n";
        
        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
        $salesRole = Role::firstOrCreate(['name' => 'salesman', 'guard_name' => 'sanctum']);

        // Definir permisos por módulo
        $permissions = [
            'create users', 'view users', 'edit users', 'delete users',
            'create clients', 'view clients', 'edit clients', 'delete clients',
            'create products', 'view products', 'edit products', 'delete products',
            'create invoices', 'view invoices', 'edit invoices', 'delete invoices'
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'sanctum']);
        }

        $adminRole->syncPermissions($permissions);
        
        $salesmanPermissions = [
            'view clients', 'create clients', 'edit clients',
            'view products', 
            'view invoices', 'create invoices', 'edit invoices'
        ];
        $salesRole->syncPermissions($salesmanPermissions);

    }

    private function createDefaultUsers(): void
    {
        
        // Usuario Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador Sistema',
                'password' => bcrypt('passwordadmin123')
            ]
        );
        $admin->assignRole('admin');

        // Usuario Vendedor
        $salesman = User::firstOrCreate(
            ['email' => 'salesman@example.com'],
            [
                'name' => 'Vendedor Ejemplo',
                'password' => bcrypt('passwordsales123')
            ]
        );
        $salesman->assignRole('salesman');

        echo "   Usuarios creados exitosamente\n";
    }

    private function createSampleClients(): void
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
                'cli_address' => 'Calle 123 #45-67, Bogotá',
                'cli_status' => 1
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
                'cli_address' => 'Carrera 45 #67-89, Medellín',
                'cli_status' => 1
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
                'cli_address' => 'Avenida 80 #123-45, Cali',
                'cli_status' => 1
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
                'cli_address' => 'Calle 78 #12-34, Bucaramanga',
                'cli_status' => 1
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
                'cli_address' => 'Transversal 12 #34-56, Barranquilla',
                'cli_status' => 1
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
                'cli_address' => 'Zona Industrial, Bogotá',
                'cli_status' => 1
            ]
        ];

        foreach ($clients as $clientData) {
            Client::firstOrCreate(
                ['cli_document_number' => $clientData['cli_document_number']],
                $clientData
            );
        }
    }

    private function createSampleProducts(): void
    {
        echo "4️⃣ Creando productos de ejemplo...\n";
        
        $products = [
            [
                'pro_name' => 'Laptop Dell Inspiron 15',
                'pro_description' => 'Laptop Dell Inspiron 15 con procesador Intel Core i5, 8GB RAM, 256GB SSD',
                'pro_unique_price' => 2500000.00,
                'pro_iva_percentage' => 19.00,
                'pro_stock' => 15,
                'pro_status' => 'active'
            ],
            [
                'pro_name' => 'Mouse Logitech MX Master 3',
                'pro_description' => 'Mouse inalámbrico ergonómico con scroll horizontal y vertical',
                'pro_unique_price' => 350000.00,
                'pro_iva_percentage' => 19.00,
                'pro_stock' => 50,
                'pro_status' => 'active'
            ],
            [
                'pro_name' => 'Teclado Mecánico RGB',
                'pro_description' => 'Teclado mecánico gaming con switches Cherry MX Blue e iluminación RGB',
                'pro_unique_price' => 450000.00,
                'pro_iva_percentage' => 19.00,
                'pro_stock' => 25,
                'pro_status' => 'active'
            ],
            [
                'pro_name' => 'Monitor Samsung 24"',
                'pro_description' => 'Monitor Samsung de 24 pulgadas Full HD 1920x1080, 75Hz',
                'pro_unique_price' => 800000.00,
                'pro_iva_percentage' => 19.00,
                'pro_stock' => 12,
                'pro_status' => 'active'
            ],
            [
                'pro_name' => 'Auriculares Sony WH-1000XM4',
                'pro_description' => 'Auriculares inalámbricos con cancelación de ruido activa',
                'pro_unique_price' => 1200000.00,
                'pro_iva_percentage' => 19.00,
                'pro_stock' => 8,
                'pro_status' => 'active'
            ],
            [
                'pro_name' => 'Webcam Logitech C920',
                'pro_description' => 'Webcam Full HD 1080p con micrófono estéreo integrado',
                'pro_unique_price' => 280000.00,
                'pro_iva_percentage' => 19.00,
                'pro_stock' => 30,
                'pro_status' => 'active'
            ],
            [
                'pro_name' => 'Tablet iPad Air',
                'pro_description' => 'iPad Air 10.9" con chip A14 Bionic, 64GB WiFi',
                'pro_unique_price' => 2800000.00,
                'pro_iva_percentage' => 19.00,
                'pro_stock' => 5,
                'pro_status' => 'inactive'
            ],
            [
                'pro_name' => 'Disco Duro Externo 1TB',
                'pro_description' => 'Disco duro externo portátil USB 3.0 de 1TB',
                'pro_unique_price' => 180000.00,
                'pro_iva_percentage' => 19.00,
                'pro_stock' => 40,
                'pro_status' => 'active'
            ],
            [
                'pro_name' => 'Impresora HP LaserJet',
                'pro_description' => 'Impresora láser monocromática con conectividad WiFi',
                'pro_unique_price' => 650000.00,
                'pro_iva_percentage' => 19.00,
                'pro_stock' => 20,
                'pro_status' => 'active'
            ],
            [
                'pro_name' => 'Router WiFi 6 TP-Link',
                'pro_description' => 'Router inalámbrico WiFi 6 con 4 antenas y velocidad hasta 1.2Gbps',
                'pro_unique_price' => 380000.00,
                'pro_iva_percentage' => 19.00,
                'pro_stock' => 35,
                'pro_status' => 'active'
            ]
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['pro_name' => $productData['pro_name']],
                $productData
            );
        }
    }
}
