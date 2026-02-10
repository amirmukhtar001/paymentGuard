<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('permission_id')->nullable()->unsigned();  // Foreign key to permissions
            $table->bigInteger('user_id')->nullable()->unsigned();  // Foreign key to users
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();  // For soft deletes

            // Optional: Add foreign key constraints if needed
            // $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_user');
    }
};
