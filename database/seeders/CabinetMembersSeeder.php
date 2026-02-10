<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabinetMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Menu
        $menu = [
            'id'            => 349,
            'parent_id'     => null,
            'title'         => 'Cabinet Members',
            'description'   => 'Manage Cabinet Members',
            'icon'          => 'bx bx-user-voice',
            'order'         => 19,
            'is_collapsible'=> 'yes',
            'created_at'    => now(),
            'updated_at'    => now(),
            'deleted_at'    => null,
        ];

        DB::table('menus')->updateOrInsert(
            ['id' => $menu['id']],
            $menu
        );

        // 2. Create Permissions
        $permissions = [
            [
                'id' => 775,
                'name' => 'Can view cabinet members',
                'slug' => 'settings.cabinet_members.view',
                'description' => null,
                'model' => 'CabinetMember',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 349,
                'show_in_menu' => 'yes',
            ],
            [
                'id' => 776,
                'name' => 'Can create cabinet members',
                'slug' => 'settings.cabinet_members.create',
                'description' => null,
                'model' => 'CabinetMember',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 349,
                'show_in_menu' => 'yes',
            ],
            [
                'id' => 777,
                'name' => 'Can edit cabinet members',
                'slug' => 'settings.cabinet_members.edit',
                'description' => null,
                'model' => 'CabinetMember',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 349,
                'show_in_menu' => 'no',
            ],
            [
                'id' => 778,
                'name' => 'Can delete cabinet members',
                'slug' => 'settings.cabinet_members.delete',
                'description' => null,
                'model' => 'CabinetMember',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 349,
                'show_in_menu' => 'no',
            ],
            [
                'id' => 779,
                'name' => 'Can view cabinet member details',
                'slug' => 'settings.cabinet_members.show',
                'description' => null,
                'model' => 'CabinetMember',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 349,
                'show_in_menu' => 'no',
            ],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['id' => $permission['id']],
                $permission
            );
        }

        // 3. Create Routes
        $routes = [
            [
                'id' => 1150,
                'is_default' => 'yes',
                'title' => 'Cabinet Members Listing',
                'description' => null,
                'route' => 'settings.cabinet_members.list',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 349,
                'permission_id' => 775,
            ],
            [
                'id' => 1151,
                'is_default' => 'no',
                'title' => 'Cabinet Members Datatable',
                'description' => null,
                'route' => 'settings.cabinet_members.datatable',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 349,
                'permission_id' => 775,
            ],
            [
                'id' => 1152,
                'is_default' => 'yes',
                'title' => 'Create Cabinet Member',
                'description' => null,
                'route' => 'settings.cabinet_members.create',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 349,
                'permission_id' => 776,
            ],
            [
                'id' => 1153,
                'is_default' => 'no',
                'title' => 'Store Cabinet Member',
                'description' => null,
                'route' => 'settings.cabinet_members.store',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 349,
                'permission_id' => 776,
            ],
            [
                'id' => 1154,
                'is_default' => 'yes',
                'title' => 'Edit Cabinet Member',
                'description' => null,
                'route' => 'settings.cabinet_members.edit',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 349,
                'permission_id' => 777,
            ],
            [
                'id' => 1155,
                'is_default' => 'no',
                'title' => 'Update Cabinet Member',
                'description' => null,
                'route' => 'settings.cabinet_members.update',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 349,
                'permission_id' => 777,
            ],
            [
                'id' => 1156,
                'is_default' => 'yes',
                'title' => 'Delete Cabinet Member',
                'description' => null,
                'route' => 'settings.cabinet_members.destroy',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 349,
                'permission_id' => 778,
            ],
            [
                'id' => 1157,
                'is_default' => 'yes',
                'title' => 'View Cabinet Member Details',
                'description' => null,
                'route' => 'settings.cabinet_members.show',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 349,
                'permission_id' => 779,
            ],
        ];

        foreach ($routes as $route) {
            DB::table('permission_routes')->updateOrInsert(
                ['id' => $route['id']],
                $route
            );
        }

        $this->command->info('Cabinet Members menu, permissions, and routes seeded successfully.');
    }
}
