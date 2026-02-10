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
        Schema::create('permission_routes', function (Blueprint $table) {

            $table->id(); // int NOT NULL AUTO_INCREMENT
            $table->char('uuid', 36)->nullable()->unique();
            $table->enum('is_default', ['yes', 'no'])->default('no');
            $table->string('title')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('route')->nullable();
            // created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('created_at')->nullable()->useCurrent();
            // updated_at DATETIME DEFAULT NULL
            $table->dateTime('updated_at')->nullable();
            // deleted_at DATETIME DEFAULT NULL
            $table->dateTime('deleted_at')->nullable();
            $table->integer('menu_id')->nullable();
            $table->integer('permission_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_routes');
    }
};
