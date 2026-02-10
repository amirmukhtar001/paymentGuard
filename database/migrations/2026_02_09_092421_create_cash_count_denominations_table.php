<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_count_denominations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_count_id')->constrained()->cascadeOnDelete();
            $table->decimal('denomination_value', 15, 2);
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();

            $table->index(['cash_count_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_count_denominations');
    }
};
