<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InvoicePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        echo "Creando permisos de facturas...\n";

        $adminRole = Role::where('name', 'admin')->where('guard_name', 'sanctum')->first();
        $salesRole = Role::where('name', 'salesman')->where('guard_name', 'sanctum')->first();

        $invoicePermissions = [
            'create invoices',
            'view invoices',
            'edit invoices',
            'delete invoices'
        ];

        foreach ($invoicePermissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'sanctum'
            ]);
            echo "Permiso creado: {$perm}\n";
        }

        if ($adminRole) {
            $allPermissions = Permission::where('guard_name', 'sanctum')->get();
            $adminRole->syncPermissions($allPermissions);
            echo "Admin: Todos los permisos de facturas asignados\n";
        }

        if ($salesRole) {
            $currentPermissions = $salesRole->permissions->pluck('name')->toArray();
            $salesmanPermissions = array_merge($currentPermissions, [
                'create invoices',
                'view invoices',
                'edit invoices'
            ]);
            $salesRole->syncPermissions($salesmanPermissions);
            echo "Salesman: Permisos de facturas asignados (sin delete)\n";
        }

        echo "✅ Permisos de facturas configurados:\n";
        echo "- Admin: create, view, edit, delete invoices\n";
        echo "- Salesman: create, view, edit invoices (sin delete)\n";
    }
}