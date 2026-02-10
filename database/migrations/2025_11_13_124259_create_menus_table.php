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
        Schema::create('menus', function (Blueprint $table) {
            $table->id(); // auto-incrementing 'id' column (equivalent to UNSIGNED INT)
            $table->char('uuid', 36)->nullable()->unique();

            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('set null'); // Foreign key for 'parent_id' referencing the same 'menus' table (self-referencing)
            $table->string('title', 255)->nullable(); // 'title' column
            $table->mediumText('description')->nullable(); // 'description' column
            $table->string('icon', 255)->nullable(); // 'icon' column

            $table->integer('order')->nullable(); // 'order' column, nullable
            $table->string('is_collapsible', 125)->nullable(); // 'is_collapsible' column, nullable
            $table->timestamps(0); // Adds 'created_at' and 'updated_at' columns with default timestamp behavior
            $table->softDeletes(); // 'deleted_at' column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
