<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusCategoryContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'id'            => 340,
                'parent_id'     => null,
                'title'         => 'Categories',
                'description'   => 'Manage content categories',
                'icon'          => 'bx bx-category',
                'order'         => 18,
                'is_collapsible'=> 'yes',
                'created_at'    => now(),
                'updated_at'    => now(),
                'deleted_at'    => null,
            ],
            [
                'id'            => 341,
                'parent_id'     => null,
                'title'         => 'Contacts',
                'description'   => 'Manage contact directory',
                'icon'          => 'bx bx-phone',
                'order'         => 19,
                'is_collapsible'=> 'yes',
                'created_at'    => now(),
                'updated_at'    => now(),
                'deleted_at'    => null,
            ],
        ];

        foreach ($menus as $menu) {
            DB::table('menus')->updateOrInsert(
                ['id' => $menu['id']],
                $menu
            );
        }
    }
}
