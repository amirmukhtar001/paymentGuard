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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('domain')->default('kp.gov.pk');
            $table->string('domain_prefix')->nullable();
            $table->string('short_code', 50)->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->enum('status', ['draft', 'active', 'inactive', 'archived'])->default('draft');
            $table->dateTime('launched_at')->nullable();
            $table->dateTime('deactivated_at')->nullable();
            $table->json('meta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'domain',
                'domain_prefix',
                'short_code',
                'contact_email',
                'contact_phone',
                'address_line1',
                'address_line2',
                'postal_code',
                'status',
                'launched_at',
                'deactivated_at',
                'meta',
            ]);
        });
    }
};
