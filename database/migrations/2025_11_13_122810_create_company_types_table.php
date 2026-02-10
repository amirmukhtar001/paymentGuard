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
        Schema::create('company_types', function (Blueprint $table) {
            $table->id(); // Adds an auto-incrementing 'id' column (equivalent to UNSIGNED BIGINT)
            $table->char('uuid', 36)->nullable()->unique();
            $table->string('title', 255)->nullable();
            $table->mediumText('description')->nullable();
            $table->timestamps(0); // Adds 'created_at' and 'updated_at' columns with default timestamp behavior
            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_types');
    }
};
