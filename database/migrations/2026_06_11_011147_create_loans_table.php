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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrower_id')->constrained('borrowers')->onDelete('cascade');
            $table->foreignId('loan_category_id')->constrained('loan_categories')->onDelete('cascade');
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('monthly_payment', 15, 2);
            $table->decimal('penalty_amount_per_month', 15, 2);
            $table->string('status')->default('Pending');
            $table->foreignId('processed_by_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
