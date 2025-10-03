<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProductPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        echo "Creando permisos de productos...\n";

        $adminRole = Role::where('name', 'admin')->where('guard_name', 'sanctum')->first();
        $salesRole = Role::where('name', 'salesman')->where('guard_name', 'sanctum')->first();

        $productPermissions = [
            'create products',
            'view products',
            'edit products',
            'delete products'
        ];

        foreach ($productPermissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'sanctum'
            ]);
            echo "Permiso creado: {$perm}\n";
        }

        if ($adminRole) {
            $allPermissions = Permission::where('guard_name', 'sanctum')->get();
            $adminRole->syncPermissions($allPermissions);
            echo "Admin: Todos los permisos de productos asignados\n";
        }

        if ($salesRole) {
            $currentPermissions = $salesRole->permissions->pluck('name')->toArray();
            $salesmanPermissions = array_merge($currentPermissions, ['view products']);
            $salesRole->syncPermissions($salesmanPermissions);
            echo "Salesman: Solo permiso 'view products' asignado\n";
        }

        echo "✅ Permisos de productos configurados:\n";
        echo "- Admin: create, view, edit, delete products\n";
        echo "- Salesman: view products (solo consulta)\n";
    }
}