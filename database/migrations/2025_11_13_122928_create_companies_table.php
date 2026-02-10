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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_type_id')->nullable()->constrained('company_types')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->string('title', 255)->nullable();
            $table->mediumText('description')->nullable();
            $table->string('prefix', 10)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
