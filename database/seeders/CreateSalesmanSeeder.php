<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CreateSalesmanSeeder extends Seeder
{
    public function run(): void
    {
        $salesRole = Role::where('name', 'salesman')->where('guard_name', 'sanctum')->first();

        $salesman = User::firstOrCreate(
            ['email' => 'vendedor@example.com'],
            [
                'name' => 'Vendedor Test',
                'password' => bcrypt('password123')
            ]
        );

        if ($salesRole) {
            $salesman->assignRole($salesRole);
            echo "vendedor@example.com / password123\n";
            echo "Permisos: create, view, edit clients (NO delete)\n";
        }
    }
}