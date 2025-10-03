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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inv_client_id')->constrained('clients')->onDelete('cascade');
            $table->string('inv_number')->required()->unique()->index();
            $table->foreignId('inv_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('inv_description')->nullable();
            $table->string('inv_notes')->nullable();
            $table->date('inv_issue_date')->required();
            $table->date('inv_due_date')->required();
            $table->decimal('inv_total_amount', 10, 2)->required();
            $table->decimal('inv_iva_percentage', 5, 2)->default(19.00);
            $table->enum('inv_status', ['pending', 'paid', 'overdue'])->default('pending');
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
