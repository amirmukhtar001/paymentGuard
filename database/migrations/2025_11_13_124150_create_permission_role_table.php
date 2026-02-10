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
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id(); // auto-incrementing 'id' column (equivalent to UNSIGNED INT)
            $table->foreignId('permission_id')->nullable()->constrained('permissions')->onDelete('set null'); // Foreign key for 'permission_id' referencing 'permissions' table
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null'); // Foreign key for 'role_id' referencing 'roles' table
            $table->timestamps(0); // Adds 'created_at' and 'updated_at' columns with default timestamp behavior
            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_role');
    }
};
