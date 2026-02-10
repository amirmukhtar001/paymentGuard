<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['id' => 2, 'parent_id' => null, 'title' => 'Menus', 'description' => null, 'icon' => 'bx bx-list-ol', 'order' => 1, 'is_collapsible' => 'no'],
            ['id' => 3, 'parent_id' => null, 'title' => 'Permissions', 'description' => null, 'icon' => 'bx bx-lock-open', 'order' => 2, 'is_collapsible' => 'no'],
            ['id' => 4, 'parent_id' => null, 'title' => 'Roles', 'description' => null, 'icon' => 'bx bx-shield-x', 'order' => 3, 'is_collapsible' => 'no'],
            ['id' => 5, 'parent_id' => null, 'title' => 'Companies', 'description' => null, 'icon' => 'bx bx-building-house', 'order' => 4, 'is_collapsible' => 'no'],
            ['id' => 7, 'parent_id' => null, 'title' => 'Sections', 'description' => null, 'icon' => 'bx bx-store', 'order' => 5, 'is_collapsible' => 'no'],
            ['id' => 8, 'parent_id' => null, 'title' => 'Users', 'description' => null, 'icon' => 'bx bx-user-pin', 'order' => 6, 'is_collapsible' => 'no'],
            ['id' => 9, 'parent_id' => null, 'title' => 'Organization Types', 'description' => null, 'icon' => 'bx bx-category-alt', 'order' => 7, 'is_collapsible' => 'no'],
            ['id' => 10, 'parent_id' => null, 'title' => 'User Logs', 'description' => null, 'icon' => 'bx bx-history', 'order' => 8, 'is_collapsible' => 'no'],
            ['id' => 11, 'parent_id' => null, 'title' => 'Settings', 'description' => null, 'icon' => 'bx bx-wrench', 'order' => 9, 'is_collapsible' => 'no'],
            ['id' => 12, 'parent_id' => null, 'title' => 'Money Control', 'description' => 'Business, branches, shifts, reconciliations', 'icon' => 'bx bx-wallet', 'order' => 10, 'is_collapsible' => 'no'],
        ];

        $now = now();
        foreach ($menus as $menu) {
            DB::table('menus')->updateOrInsert(
                ['id' => $menu['id']],
                array_merge($menu, ['created_at' => $now, 'updated_at' => $now, 'deleted_at' => null])
            );
        }
    }
}
