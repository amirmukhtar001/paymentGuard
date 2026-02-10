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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id(); // auto-incrementing 'id' column (equivalent to UNSIGNED INT)
            $table->char('uuid', 36)->nullable()->unique();

            $table->string('name', 255); // 'name' column, not nullable
            $table->string('slug', 255); // 'slug' column, not nullable
            $table->mediumText('description')->nullable(); // 'description' column, nullable
            $table->string('model', 255)->nullable(); // 'model' column, nullable
            $table->integer('menu_id')->nullable(); // 'menu_id' column, nullable
            $table->enum('show_in_menu', ['yes', 'no'])->default('no'); // 'show_in_menu' column with default value 'no'
            $table->timestamps(0); // Adds 'created_at' and 'updated_at' columns with default timestamp behavior
            $table->softDeletes(); // 'deleted_at' column for soft deletes

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
