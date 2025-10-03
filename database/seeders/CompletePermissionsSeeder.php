<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CompletePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        echo "Iniciando configuración de permisos...\n";

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
        $salesRole = Role::firstOrCreate(['name' => 'salesman', 'guard_name' => 'sanctum']);

        $allPermissions = [
            'create users',
            'view users',
            'edit users', 
            'delete users',
            'create clients',
            'view clients',
            'edit clients',
            'delete clients'
        ];

        foreach ($allPermissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'sanctum'
            ]);
            echo "Permiso creado: {$perm}\n";
        }

        $adminRole->syncPermissions($allPermissions);
        echo "Admin role: Todos los permisos asignados\n";

        $salesmanPermissions = [
            'create clients',
            'view clients',
            'edit clients'
        ];
        $salesRole->syncPermissions($salesmanPermissions);
        echo "Salesman role: Permisos de clientes asignados (sin delete)\n";

        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            $admin->assignRole($adminRole);
            echo "Usuario admin configurado correctamente\n";
        }

        echo "✅ Configuración de permisos completada\n";
    }
}