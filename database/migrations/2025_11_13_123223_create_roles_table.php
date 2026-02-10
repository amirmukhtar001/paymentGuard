<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // auto-incrementing 'id' column (equivalent to UNSIGNED INT)
            $table->char('uuid', 36)->nullable()->unique();

            $table->string('name', 255); // 'name' column, not nullable
            $table->string('slug', 255); // 'slug' column, not nullable
            $table->string('description', 255)->nullable(); // 'description' column, nullable
            $table->integer('level')->default(1); // 'level' column, default value of 1
            $table->timestamps(0); // Adds 'created_at' and 'updated_at' columns with default timestamp behavior
            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
