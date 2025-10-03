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
        Schema::create('invoice_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('if_invoice_id')->required()->constrained('invoices')->cascadeOnDelete();
            $table->string('if_file_path')->required();
            $table->string('if_file_name')->required();
            $table->string('if_file_type')->required();
            $table->unsignedBigInteger('if_file_size')->required();
            $table->string('if_mime_type')->nullable();
            $table->text('if_encrypted_path')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_files');
    }
};
