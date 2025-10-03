<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
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
            ]
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['pro_name' => $productData['pro_name']],
                $productData
            );
        }

        echo "✅ Productos de ejemplo creados\n";
    }
}