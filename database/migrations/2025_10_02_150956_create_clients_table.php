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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('cli_first_name')->required();
            $table->string('cli_middle_name')->nullable();
            $table->string('cli_last_name')->required();
            $table->string('cli_second_last_name')->nullable();
            $table->string('cli_document_type')->required();
            $table->string('cli_document_number')->required()->unique()->index();
            $table->string('cli_email')->required()->unique();
            $table->string('cli_phone')->required();
            $table->string('cli_address')->nullable();
            $table->tinyInteger('cli_status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->index(['cli_first_name', 'cli_last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
