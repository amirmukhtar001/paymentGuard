<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('role_user')->updateOrInsert(
            ['role_id' => 1, 'user_id' => 1],
            ['created_at' => $now, 'updated_at' => $now, 'deleted_at' => null]
        );
    }
}
