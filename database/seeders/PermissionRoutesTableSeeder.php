<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoutesTableSeeder extends Seeder
{
    /**
     * Seed permission_routes for routes that exist in the application.
     */
    public function run(): void
    {
        $now = now();
        $routes = [
            ['route' => 'settings.menus.list', 'menu_id' => 2, 'permission_id' => 1, 'title' => 'All menus'],
            ['route' => 'settings.menus.create', 'menu_id' => 2, 'permission_id' => 2, 'title' => 'Create menu'],
            ['route' => 'settings.menus.store', 'menu_id' => 2, 'permission_id' => 2, 'title' => 'Store menu'],
            ['route' => 'settings.menus.edit', 'menu_id' => 2, 'permission_id' => 3, 'title' => 'Edit menu'],
            ['route' => 'settings.menus.update', 'menu_id' => 2, 'permission_id' => 3, 'title' => 'Update menu'],
            ['route' => 'settings.menus.delete', 'menu_id' => 2, 'permission_id' => 4, 'title' => 'Delete menu'],
            ['route' => 'settings.my-permissions.list', 'menu_id' => 3, 'permission_id' => 5, 'title' => 'All permissions'],
            ['route' => 'settings.my-permissions.create', 'menu_id' => 3, 'permission_id' => 6, 'title' => 'Create permission'],
            ['route' => 'settings.my-permissions.store', 'menu_id' => 3, 'permission_id' => 6, 'title' => 'Store permission'],
            ['route' => 'settings.my-permissions.edit', 'menu_id' => 3, 'permission_id' => 7, 'title' => 'Edit permission'],
            ['route' => 'settings.my-permissions.update', 'menu_id' => 3, 'permission_id' => 7, 'title' => 'Update permission'],
            ['route' => 'settings.my-permissions.delete', 'menu_id' => 3, 'permission_id' => 8, 'title' => 'Delete permission'],
            ['route' => 'settings.my-roles.list', 'menu_id' => 4, 'permission_id' => 9, 'title' => 'All roles'],
            ['route' => 'settings.my-roles.create', 'menu_id' => 4, 'permission_id' => 10, 'title' => 'Create role'],
            ['route' => 'settings.my-roles.store', 'menu_id' => 4, 'permission_id' => 10, 'title' => 'Store role'],
            ['route' => 'settings.my-roles.edit', 'menu_id' => 4, 'permission_id' => 11, 'title' => 'Edit role'],
            ['route' => 'settings.my-roles.update', 'menu_id' => 4, 'permission_id' => 11, 'title' => 'Update role'],
            ['route' => 'settings.my-roles.delete', 'menu_id' => 4, 'permission_id' => 12, 'title' => 'Delete role'],
            ['route' => 'settings.my-roles.show', 'menu_id' => 4, 'permission_id' => 13, 'title' => 'Role permissions'],
            ['route' => 'settings.my-roles.role-permissions-save', 'menu_id' => 4, 'permission_id' => 13, 'title' => 'Save role permissions'],
            ['route' => 'settings.companies.list', 'menu_id' => 5, 'permission_id' => 14, 'title' => 'All companies'],
            ['route' => 'settings.companies.create', 'menu_id' => 5, 'permission_id' => 15, 'title' => 'Create company'],
            ['route' => 'settings.companies.store', 'menu_id' => 5, 'permission_id' => 15, 'title' => 'Store company'],
            ['route' => 'settings.companies.edit', 'menu_id' => 5, 'permission_id' => 16, 'title' => 'Edit company'],
            ['route' => 'settings.companies.update', 'menu_id' => 5, 'permission_id' => 16, 'title' => 'Update company'],
            ['route' => 'settings.companies.delete', 'menu_id' => 5, 'permission_id' => 17, 'title' => 'Delete company'],
            ['route' => 'settings.sections.list', 'menu_id' => 7, 'permission_id' => 18, 'title' => 'All sections'],
            ['route' => 'settings.sections.create', 'menu_id' => 7, 'permission_id' => 19, 'title' => 'Create section'],
            ['route' => 'settings.sections.store', 'menu_id' => 7, 'permission_id' => 19, 'title' => 'Store section'],
            ['route' => 'settings.sections.edit', 'menu_id' => 7, 'permission_id' => 20, 'title' => 'Edit section'],
            ['route' => 'settings.sections.update', 'menu_id' => 7, 'permission_id' => 20, 'title' => 'Update section'],
            ['route' => 'settings.sections.delete', 'menu_id' => 7, 'permission_id' => 21, 'title' => 'Delete section'],
            ['route' => 'settings.users-mgt.list', 'menu_id' => 8, 'permission_id' => 22, 'title' => 'All users'],
            ['route' => 'settings.users-mgt.create', 'menu_id' => 8, 'permission_id' => 23, 'title' => 'Create user'],
            ['route' => 'settings.users-mgt.store', 'menu_id' => 8, 'permission_id' => 23, 'title' => 'Store user'],
            ['route' => 'settings.users-mgt.edit', 'menu_id' => 8, 'permission_id' => 24, 'title' => 'Edit user'],
            ['route' => 'settings.users-mgt.update', 'menu_id' => 8, 'permission_id' => 24, 'title' => 'Update user'],
            ['route' => 'settings.users-mgt.delete', 'menu_id' => 8, 'permission_id' => 25, 'title' => 'Delete user'],
            ['route' => 'settings.users-mgt.show', 'menu_id' => 8, 'permission_id' => 26, 'title' => 'User permissions'],
            ['route' => 'settings.users-mgt.user-permissions-save', 'menu_id' => 8, 'permission_id' => 26, 'title' => 'Save user permissions'],
            ['route' => 'settings.company-types.list', 'menu_id' => 9, 'permission_id' => 27, 'title' => 'All organization types'],
            ['route' => 'settings.company-types.create', 'menu_id' => 9, 'permission_id' => 28, 'title' => 'Create organization type'],
            ['route' => 'settings.company-types.store', 'menu_id' => 9, 'permission_id' => 28, 'title' => 'Store organization type'],
            ['route' => 'settings.company-types.edit', 'menu_id' => 9, 'permission_id' => 29, 'title' => 'Edit organization type'],
            ['route' => 'settings.company-types.update', 'menu_id' => 9, 'permission_id' => 29, 'title' => 'Update organization type'],
            ['route' => 'settings.company-types.delete', 'menu_id' => 9, 'permission_id' => 30, 'title' => 'Delete organization type'],
            ['route' => 'settings.user_logs.index', 'menu_id' => 10, 'permission_id' => 31, 'title' => 'User logs'],
            ['route' => 'settings.user_logs.list', 'menu_id' => 10, 'permission_id' => 31, 'title' => 'User logs list'],
            ['route' => 'settings.settings', 'menu_id' => 11, 'permission_id' => 32, 'title' => 'App settings'],
            ['route' => 'settings.save', 'menu_id' => 11, 'permission_id' => 32, 'title' => 'Save settings'],
        ];

        foreach ($routes as $i => $row) {
            DB::table('permission_routes')->updateOrInsert(
                ['route' => $row['route'], 'menu_id' => $row['menu_id']],
                [
                    'is_default' => 'yes',
                    'title' => $row['title'],
                    'description' => null,
                    'route' => $row['route'],
                    'menu_id' => $row['menu_id'],
                    'permission_id' => $row['permission_id'],
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]
            );
        }
    }
}
