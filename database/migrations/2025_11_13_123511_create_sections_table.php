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
        Schema::create('sections', function (Blueprint $table) {
            $table->id(); // Adds an auto-incrementing 'id' column (equivalent to UNSIGNED INT)
            $table->char('uuid', 36)->nullable()->unique();

            $table->foreignId('parent_id')->nullable()->constrained('sections')->onDelete('set null'); // Foreign key for 'parent_id' referencing the same table (self-referencing)
            $table->string('title', 255)->nullable(); // 'title' column
            $table->mediumText('description')->nullable(); // 'description' column
            $table->tinyInteger('is_active')->default(1); // 'is_active' column, default value of 1
            $table->integer('user_id')->nullable(); // 'user_id' column, nullable
            $table->timestamps(0); // Adds 'created_at' and 'updated_at' columns with default timestamp behavior
            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
