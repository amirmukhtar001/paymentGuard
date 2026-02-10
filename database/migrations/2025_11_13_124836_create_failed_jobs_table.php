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
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // auto-incrementing 'id' column (equivalent to UNSIGNED BIGINT)
            $table->string('uuid', 255); // 'uuid' column
            $table->mediumText('connection'); // 'connection' column
            $table->mediumText('queue'); // 'queue' column
            $table->longText('payload'); // 'payload' column
            $table->longText('exception'); // 'exception' column
            $table->timestamp('failed_at')->default(DB::raw('CURRENT_TIMESTAMP')); // 'failed_at' column

            $table->softDeletes(); // 'deleted_at' column for soft deletes
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
    }
};
