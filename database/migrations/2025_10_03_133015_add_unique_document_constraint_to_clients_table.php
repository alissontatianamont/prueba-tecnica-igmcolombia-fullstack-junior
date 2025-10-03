<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Primero eliminar la restricción unique existente en cli_document_number
            $table->dropUnique(['cli_document_number']);
            
            // Agregar restricción unique compuesta para tipo y número de documento
            $table->unique(['cli_document_type', 'cli_document_number'], 'clients_document_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Eliminar la restricción compuesta
            $table->dropUnique('clients_document_unique');
            
            // Restaurar la restricción unique individual
            $table->unique('cli_document_number');
        });
    }
};
