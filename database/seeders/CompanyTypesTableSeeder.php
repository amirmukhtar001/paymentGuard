<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $types = [
            ['title' => 'Organization', 'description' => null],
            ['title' => 'Department', 'description' => null],
        ];

        foreach ($types as $i => $type) {
            DB::table('company_types')->updateOrInsert(
                ['id' => $i + 1],
                array_merge($type, ['created_at' => $now, 'updated_at' => $now, 'deleted_at' => null])
            );
        }
    }
}
