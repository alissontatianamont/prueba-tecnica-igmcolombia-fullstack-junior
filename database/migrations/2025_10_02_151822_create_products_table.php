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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('pro_name')->required();
            $table->string('pro_description')->nullable();
            $table->decimal('pro_unique_price', 10, 2)->required();
            $table->decimal('pro_iva_percentage', 5, 2)->required()->default(19.00);
            $table->unsignedInteger('pro_stock')->required()->default(0);
            $table->enum('pro_status', ['active', 'inactive'])->default('active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
