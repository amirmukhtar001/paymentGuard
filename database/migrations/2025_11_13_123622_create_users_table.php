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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('password', 255);
            $table->string('remember_token', 100)->nullable();
            $table->string('username', 255)->nullable()->unique();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('status')->default(1);
            $table->mediumText('description')->nullable();
            $table->string('contact_number', 125)->nullable();
            $table->boolean('is_otp_enabled')->default(false);
            $table->string('pincode', 50)->nullable();
            $table->boolean('is_pincode_enabled')->default(false);
            $table->string('otp', 4)->nullable();
            $table->dateTime('otp_time')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
