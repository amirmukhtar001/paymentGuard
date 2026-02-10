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
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id(); // auto-incrementing 'id' column (equivalent to UNSIGNED BIGINT)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Foreign key for 'user_id' referencing 'users' table
            $table->string('action', 50); // 'action' column, not nullable
            $table->string('ip_address', 45)->nullable(); // 'ip_address' column
            $table->mediumText('user_agent')->nullable(); // 'user_agent' column
            $table->timestamps(0); // Adds 'created_at' and 'updated_at' columns with default timestamp behavior
            $table->softDeletes(); // 'deleted_at' column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
