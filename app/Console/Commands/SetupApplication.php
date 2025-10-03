<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup {--fresh : Run fresh migrations before seeding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configura la aplicación con datos iniciales (roles, usuarios, clientes y productos)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando configuración de la prueba técnica para IGM Colombia...');
        $this->newLine();

        // Ejecutar seeders
        $this->info('Ejecutando seeders...');
        Artisan::call('db:seed', [], $this->getOutput());
        
        $this->newLine();
        $this->info('Configuración completada exitosamente!');
        $this->newLine();
        
        $this->table(
            ['Usuario', 'Email', 'Password', 'Rol'],
            [
                ['Administrador', 'admin@example.com', 'passwordadmin123', 'admin'],
                ['Vendedor', 'salesman@example.com', 'passwordsales123', 'salesman']
            ]
        );
        $this->newLine();
        $this->info('Consulta el README.md para documentación completa');

        return 0;
    }
}
