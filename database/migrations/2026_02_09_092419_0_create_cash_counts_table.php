<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            $table->foreignId('counted_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('counted_at');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('status', 20)->default('draft');
            $table->text('notes')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();

            $table->index(['shift_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_counts');
    }
};
