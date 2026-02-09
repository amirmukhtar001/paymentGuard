<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pos_sales_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cash_count_id')->constrained()->cascadeOnDelete();
            $table->decimal('expected_amount', 15, 2);
            $table->decimal('actual_amount', 15, 2);
            $table->decimal('difference_amount', 15, 2);
            $table->string('difference_type', 20);
            $table->string('status', 30)->default('pending_review');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('shift_id');
            $table->index(['business_id', 'status']);
            $table->index(['branch_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reconciliations');
    }
};
