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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ii_invoice_id')->required()->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('ii_product_id')->required()->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('ii_quantity')->required()->default(1);
            $table->decimal('ii_unit_price', 10, 2)->required();
            $table->decimal('ii_iva_percentage', 5, 2)->required()->default(19.00);
            $table->decimal('ii_total_price', 10, 2)->required();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
