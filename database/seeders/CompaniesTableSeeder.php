<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('companies')->updateOrInsert(
            ['id' => 1],
            [
                'company_type_id' => 1,
                'parent_id' => null,
                'title' => 'Main',
                'description' => null,
                'prefix' => null,
                'user_id' => null,
                'domain' => 'localhost',
                'domain_prefix' => null,
                'short_code' => null,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );
    }
}
