<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleCategoryContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->insert([
            ['permission_id' => 642, 'role_id' => 4, 'created_at' => '2025-10-16 14:31:01', 'updated_at' => '2025-10-16 19:31:01', 'deleted_at' => null],
            ['permission_id' => 643, 'role_id' => 4, 'created_at' => '2025-10-16 14:31:01', 'updated_at' => '2025-10-16 19:31:01', 'deleted_at' => null],
            ['permission_id' => 644, 'role_id' => 4, 'created_at' => '2025-10-16 14:31:01', 'updated_at' => '2025-10-16 19:31:01', 'deleted_at' => null],
            ['permission_id' => 645, 'role_id' => 4, 'created_at' => '2025-10-16 14:31:01', 'updated_at' => '2025-10-16 19:31:01', 'deleted_at' => null],
            ['permission_id' => 646, 'role_id' => 4, 'created_at' => '2025-10-16 14:31:01', 'updated_at' => '2025-10-16 19:31:01', 'deleted_at' => null],
            ['permission_id' => 647, 'role_id' => 4, 'created_at' => '2025-10-16 14:31:01', 'updated_at' => '2025-10-16 19:31:01', 'deleted_at' => null],
            ['permission_id' => 648, 'role_id' => 4, 'created_at' => '2025-10-16 14:31:01', 'updated_at' => '2025-10-16 19:31:01', 'deleted_at' => null],
            ['permission_id' => 649, 'role_id' => 4, 'created_at' => '2025-10-16 14:31:01', 'updated_at' => '2025-10-16 19:31:01', 'deleted_at' => null],
            ['permission_id' => 650, 'role_id' => 4, 'created_at' => '2025-10-16 14:31:01', 'updated_at' => '2025-10-16 19:31:01', 'deleted_at' => null],
            ['permission_id' => 651, 'role_id' => 4, 'created_at' => '2025-10-16 14:31:01', 'updated_at' => '2025-10-16 19:31:01', 'deleted_at' => null]

        ]);
    }
}
