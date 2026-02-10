<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Role Types
         *
         */
        $RoleItems = [
            [
                'id'          => 1,
                'name'        => 'Super Admin',
                'slug'        => 'super-admin',
                'description' => 'Super Admin role for system',
                'level'       => 4,
            ],
                [
                'id'          => 2,
                'name'        => 'Admin',
                'slug'        => 'admin',
                'description' => 'Admin role for system',
                'level'       => 4,
            ]
        ];

        foreach ($RoleItems as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                $role
            );
        }
    }
}
