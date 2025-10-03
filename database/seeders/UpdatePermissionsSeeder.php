<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdatePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'sanctum')->first();
        $salesRole = Role::where('name', 'salesman')->where('guard_name', 'sanctum')->first();

        $clientPermissions = [
            'create clients',
            'view clients', 
            'edit clients',
            'delete clients'
        ];

        foreach ($clientPermissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm, 
                'guard_name' => 'sanctum'
            ]);
        }

        if ($adminRole) {
            $allPermissions = Permission::where('guard_name', 'sanctum')->get();
            $adminRole->syncPermissions($allPermissions);
        }

        if ($salesRole) {
            $salesmanPermissions = [
                'create clients',
                'view clients', 
                'edit clients'
            ];
            $salesRole->syncPermissions($salesmanPermissions);
        }

        echo "Permisos actualizados correctamente:\n";
        echo "- Admin: Todos los permisos\n";
        echo "- Salesman: create, view, edit clients (NO delete)\n";
    }
}