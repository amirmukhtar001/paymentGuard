<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_sales_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            $table->string('source_type', 30)->default('manual');
            $table->string('external_reference', 100)->nullable();
            $table->decimal('sales_gross', 15, 2)->default(0);
            $table->decimal('discounts', 15, 2)->default(0);
            $table->decimal('returns', 15, 2)->default(0);
            $table->decimal('net_cash_sales', 15, 2);
            $table->string('currency', 3)->default('PKR');
            $table->foreignId('entered_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();

            $table->index(['shift_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_sales_records');
    }
};
