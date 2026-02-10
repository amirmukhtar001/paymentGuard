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

        // Clean up any legacy business.show menu entry that required a parameter
        DB::table('permission_routes')
            ->where('route', 'business.show')
            ->delete();

        $routes = [
            // Menus: only list + create visible
            ['route' => 'settings.menus.list', 'menu_id' => 2, 'permission_id' => 1, 'title' => 'All menus', 'is_default' => 'yes'],
            ['route' => 'settings.menus.create', 'menu_id' => 2, 'permission_id' => 2, 'title' => 'Create menu', 'is_default' => 'yes'],
            ['route' => 'settings.menus.store', 'menu_id' => 2, 'permission_id' => 2, 'title' => 'Store menu', 'is_default' => 'no'],
            ['route' => 'settings.menus.edit', 'menu_id' => 2, 'permission_id' => 3, 'title' => 'Edit menu', 'is_default' => 'no'],
            ['route' => 'settings.menus.update', 'menu_id' => 2, 'permission_id' => 3, 'title' => 'Update menu', 'is_default' => 'no'],
            ['route' => 'settings.menus.delete', 'menu_id' => 2, 'permission_id' => 4, 'title' => 'Delete menu', 'is_default' => 'no'],
            // Permissions: only list + create visible
            ['route' => 'settings.my-permissions.list', 'menu_id' => 3, 'permission_id' => 5, 'title' => 'All permissions', 'is_default' => 'yes'],
            ['route' => 'settings.my-permissions.create', 'menu_id' => 3, 'permission_id' => 6, 'title' => 'Create permission', 'is_default' => 'yes'],
            ['route' => 'settings.my-permissions.store', 'menu_id' => 3, 'permission_id' => 6, 'title' => 'Store permission', 'is_default' => 'no'],
            ['route' => 'settings.my-permissions.edit', 'menu_id' => 3, 'permission_id' => 7, 'title' => 'Edit permission', 'is_default' => 'no'],
            ['route' => 'settings.my-permissions.update', 'menu_id' => 3, 'permission_id' => 7, 'title' => 'Update permission', 'is_default' => 'no'],
            ['route' => 'settings.my-permissions.delete', 'menu_id' => 3, 'permission_id' => 8, 'title' => 'Delete permission', 'is_default' => 'no'],
            // Roles: only list + create visible
            ['route' => 'settings.my-roles.list', 'menu_id' => 4, 'permission_id' => 9, 'title' => 'All roles', 'is_default' => 'yes'],
            ['route' => 'settings.my-roles.create', 'menu_id' => 4, 'permission_id' => 10, 'title' => 'Create role', 'is_default' => 'yes'],
            ['route' => 'settings.my-roles.store', 'menu_id' => 4, 'permission_id' => 10, 'title' => 'Store role', 'is_default' => 'no'],
            ['route' => 'settings.my-roles.edit', 'menu_id' => 4, 'permission_id' => 11, 'title' => 'Edit role', 'is_default' => 'no'],
            ['route' => 'settings.my-roles.update', 'menu_id' => 4, 'permission_id' => 11, 'title' => 'Update role', 'is_default' => 'no'],
            ['route' => 'settings.my-roles.delete', 'menu_id' => 4, 'permission_id' => 12, 'title' => 'Delete role', 'is_default' => 'no'],
            ['route' => 'settings.my-roles.show', 'menu_id' => 4, 'permission_id' => 13, 'title' => 'Role permissions', 'is_default' => 'no'],
            ['route' => 'settings.my-roles.role-permissions-save', 'menu_id' => 4, 'permission_id' => 13, 'title' => 'Save role permissions', 'is_default' => 'no'],
            // Companies: only list + create visible
            ['route' => 'settings.companies.list', 'menu_id' => 5, 'permission_id' => 14, 'title' => 'All companies', 'is_default' => 'yes'],
            ['route' => 'settings.companies.create', 'menu_id' => 5, 'permission_id' => 15, 'title' => 'Create company', 'is_default' => 'yes'],
            ['route' => 'settings.companies.store', 'menu_id' => 5, 'permission_id' => 15, 'title' => 'Store company', 'is_default' => 'no'],
            ['route' => 'settings.companies.edit', 'menu_id' => 5, 'permission_id' => 16, 'title' => 'Edit company', 'is_default' => 'no'],
            ['route' => 'settings.companies.update', 'menu_id' => 5, 'permission_id' => 16, 'title' => 'Update company', 'is_default' => 'no'],
            ['route' => 'settings.companies.delete', 'menu_id' => 5, 'permission_id' => 17, 'title' => 'Delete company', 'is_default' => 'no'],
            // Sections: only list + create visible
            ['route' => 'settings.sections.list', 'menu_id' => 7, 'permission_id' => 18, 'title' => 'All sections', 'is_default' => 'yes'],
            ['route' => 'settings.sections.create', 'menu_id' => 7, 'permission_id' => 19, 'title' => 'Create section', 'is_default' => 'yes'],
            ['route' => 'settings.sections.store', 'menu_id' => 7, 'permission_id' => 19, 'title' => 'Store section', 'is_default' => 'no'],
            ['route' => 'settings.sections.edit', 'menu_id' => 7, 'permission_id' => 20, 'title' => 'Edit section', 'is_default' => 'no'],
            ['route' => 'settings.sections.update', 'menu_id' => 7, 'permission_id' => 20, 'title' => 'Update section', 'is_default' => 'no'],
            ['route' => 'settings.sections.delete', 'menu_id' => 7, 'permission_id' => 21, 'title' => 'Delete section', 'is_default' => 'no'],
            // Users: only list + create visible
            ['route' => 'settings.users-mgt.list', 'menu_id' => 8, 'permission_id' => 22, 'title' => 'All users', 'is_default' => 'yes'],
            ['route' => 'settings.users-mgt.create', 'menu_id' => 8, 'permission_id' => 23, 'title' => 'Create user', 'is_default' => 'yes'],
            ['route' => 'settings.users-mgt.store', 'menu_id' => 8, 'permission_id' => 23, 'title' => 'Store user', 'is_default' => 'no'],
            ['route' => 'settings.users-mgt.edit', 'menu_id' => 8, 'permission_id' => 24, 'title' => 'Edit user', 'is_default' => 'no'],
            ['route' => 'settings.users-mgt.update', 'menu_id' => 8, 'permission_id' => 24, 'title' => 'Update user', 'is_default' => 'no'],
            ['route' => 'settings.users-mgt.delete', 'menu_id' => 8, 'permission_id' => 25, 'title' => 'Delete user', 'is_default' => 'no'],
            ['route' => 'settings.users-mgt.show', 'menu_id' => 8, 'permission_id' => 26, 'title' => 'User permissions', 'is_default' => 'no'],
            ['route' => 'settings.users-mgt.user-permissions-save', 'menu_id' => 8, 'permission_id' => 26, 'title' => 'Save user permissions', 'is_default' => 'no'],
            // Company types: only list + create visible
            ['route' => 'settings.company-types.list', 'menu_id' => 9, 'permission_id' => 27, 'title' => 'All organization types', 'is_default' => 'yes'],
            ['route' => 'settings.company-types.create', 'menu_id' => 9, 'permission_id' => 28, 'title' => 'Create organization type', 'is_default' => 'yes'],
            ['route' => 'settings.company-types.store', 'menu_id' => 9, 'permission_id' => 28, 'title' => 'Store organization type', 'is_default' => 'no'],
            ['route' => 'settings.company-types.edit', 'menu_id' => 9, 'permission_id' => 29, 'title' => 'Edit organization type', 'is_default' => 'no'],
            ['route' => 'settings.company-types.update', 'menu_id' => 9, 'permission_id' => 29, 'title' => 'Update organization type', 'is_default' => 'no'],
            ['route' => 'settings.company-types.delete', 'menu_id' => 9, 'permission_id' => 30, 'title' => 'Delete organization type', 'is_default' => 'no'],
            // User logs: listing only
            ['route' => 'settings.user_logs.index', 'menu_id' => 10, 'permission_id' => 31, 'title' => 'User logs', 'is_default' => 'yes'],
            ['route' => 'settings.user_logs.list', 'menu_id' => 10, 'permission_id' => 31, 'title' => 'User logs list', 'is_default' => 'no'],
            // Settings: listing only
            ['route' => 'settings.settings', 'menu_id' => 11, 'permission_id' => 32, 'title' => 'App settings', 'is_default' => 'yes'],
            ['route' => 'settings.save', 'menu_id' => 11, 'permission_id' => 32, 'title' => 'Save settings', 'is_default' => 'no'],
            // Money Control (business module)
            ['route' => 'dashboard', 'menu_id' => 12, 'permission_id' => 33, 'title' => 'Dashboard', 'is_default' => 'yes'],
            ['route' => 'branches.index', 'menu_id' => 12, 'permission_id' => 34, 'title' => 'Branches', 'is_default' => 'yes'],
            ['route' => 'shifts.index', 'menu_id' => 12, 'permission_id' => 35, 'title' => 'Shifts', 'is_default' => 'yes'],
            ['route' => 'reconciliations.index', 'menu_id' => 12, 'permission_id' => 36, 'title' => 'Reconciliations', 'is_default' => 'yes'],
            ['route' => 'business.create', 'menu_id' => 12, 'permission_id' => 37, 'title' => 'Business setup', 'is_default' => 'yes'],
        ];

        foreach ($routes as $i => $row) {
            DB::table('permission_routes')->updateOrInsert(
                ['route' => $row['route'], 'menu_id' => $row['menu_id']],
                [
                    'is_default' => $row['is_default'] ?? 'yes',
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
