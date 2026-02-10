<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $permissions = [
            // Menus (menu_id 2)
            ['id' => 1, 'name' => 'View menus', 'slug' => 'settings.menus.list', 'description' => null, 'model' => 'Menu', 'menu_id' => 2, 'show_in_menu' => 'yes'],
            ['id' => 2, 'name' => 'Create menu', 'slug' => 'settings.menus.create', 'description' => null, 'model' => 'Menu', 'menu_id' => 2, 'show_in_menu' => 'yes'],
            ['id' => 3, 'name' => 'Edit menu', 'slug' => 'settings.menus.edit', 'description' => null, 'model' => 'Menu', 'menu_id' => 2, 'show_in_menu' => 'no'],
            ['id' => 4, 'name' => 'Delete menu', 'slug' => 'settings.menus.delete', 'description' => null, 'model' => 'Menu', 'menu_id' => 2, 'show_in_menu' => 'no'],
            // Permissions (menu_id 3)
            ['id' => 5, 'name' => 'View permissions', 'slug' => 'settings.my-permissions.list', 'description' => null, 'model' => 'MyPermission', 'menu_id' => 3, 'show_in_menu' => 'yes'],
            ['id' => 6, 'name' => 'Create permission', 'slug' => 'settings.my-permissions.create', 'description' => null, 'model' => 'MyPermission', 'menu_id' => 3, 'show_in_menu' => 'yes'],
            ['id' => 7, 'name' => 'Edit permission', 'slug' => 'settings.my-permissions.edit', 'description' => null, 'model' => 'MyPermission', 'menu_id' => 3, 'show_in_menu' => 'no'],
            ['id' => 8, 'name' => 'Delete permission', 'slug' => 'settings.my-permissions.delete', 'description' => null, 'model' => 'MyPermission', 'menu_id' => 3, 'show_in_menu' => 'no'],
            // Roles (menu_id 4)
            ['id' => 9, 'name' => 'View roles', 'slug' => 'settings.my-roles.list', 'description' => null, 'model' => 'MyRole', 'menu_id' => 4, 'show_in_menu' => 'yes'],
            ['id' => 10, 'name' => 'Create role', 'slug' => 'settings.my-roles.create', 'description' => null, 'model' => 'MyRole', 'menu_id' => 4, 'show_in_menu' => 'yes'],
            ['id' => 11, 'name' => 'Edit role', 'slug' => 'settings.my-roles.edit', 'description' => null, 'model' => 'MyRole', 'menu_id' => 4, 'show_in_menu' => 'no'],
            ['id' => 12, 'name' => 'Delete role', 'slug' => 'settings.my-roles.delete', 'description' => null, 'model' => 'MyRole', 'menu_id' => 4, 'show_in_menu' => 'no'],
            ['id' => 13, 'name' => 'Assign permissions to role', 'slug' => 'settings.my-roles.assign', 'description' => null, 'model' => 'MyRole', 'menu_id' => 4, 'show_in_menu' => 'no'],
            // Companies (menu_id 5)
            ['id' => 14, 'name' => 'View companies', 'slug' => 'settings.companies.list', 'description' => null, 'model' => 'Company', 'menu_id' => 5, 'show_in_menu' => 'yes'],
            ['id' => 15, 'name' => 'Create company', 'slug' => 'settings.companies.create', 'description' => null, 'model' => 'Company', 'menu_id' => 5, 'show_in_menu' => 'yes'],
            ['id' => 16, 'name' => 'Edit company', 'slug' => 'settings.companies.edit', 'description' => null, 'model' => 'Company', 'menu_id' => 5, 'show_in_menu' => 'no'],
            ['id' => 17, 'name' => 'Delete company', 'slug' => 'settings.companies.delete', 'description' => null, 'model' => 'Company', 'menu_id' => 5, 'show_in_menu' => 'no'],
            // Sections (menu_id 7)
            ['id' => 18, 'name' => 'View sections', 'slug' => 'settings.sections.list', 'description' => null, 'model' => 'Section', 'menu_id' => 7, 'show_in_menu' => 'yes'],
            ['id' => 19, 'name' => 'Create section', 'slug' => 'settings.sections.create', 'description' => null, 'model' => 'Section', 'menu_id' => 7, 'show_in_menu' => 'yes'],
            ['id' => 20, 'name' => 'Edit section', 'slug' => 'settings.sections.edit', 'description' => null, 'model' => 'Section', 'menu_id' => 7, 'show_in_menu' => 'no'],
            ['id' => 21, 'name' => 'Delete section', 'slug' => 'settings.sections.delete', 'description' => null, 'model' => 'Section', 'menu_id' => 7, 'show_in_menu' => 'no'],
            // Users (menu_id 8)
            ['id' => 22, 'name' => 'View users', 'slug' => 'users.mgt.list', 'description' => null, 'model' => 'User', 'menu_id' => 8, 'show_in_menu' => 'yes'],
            ['id' => 23, 'name' => 'Create user', 'slug' => 'users.mgt.create', 'description' => null, 'model' => 'User', 'menu_id' => 8, 'show_in_menu' => 'yes'],
            ['id' => 24, 'name' => 'Edit user', 'slug' => 'users.mgt.edit', 'description' => null, 'model' => 'User', 'menu_id' => 8, 'show_in_menu' => 'no'],
            ['id' => 25, 'name' => 'Delete user', 'slug' => 'users.mgt.delete', 'description' => null, 'model' => 'User', 'menu_id' => 8, 'show_in_menu' => 'no'],
            ['id' => 26, 'name' => 'Assign permissions to user', 'slug' => 'users.mgt.assign.permissions', 'description' => null, 'model' => 'User', 'menu_id' => 8, 'show_in_menu' => 'no'],
            // Organization types (menu_id 9)
            ['id' => 27, 'name' => 'View organization types', 'slug' => 'settings.company-types.list', 'description' => null, 'model' => 'CompanyType', 'menu_id' => 9, 'show_in_menu' => 'yes'],
            ['id' => 28, 'name' => 'Create organization type', 'slug' => 'settings.company-types.create', 'description' => null, 'model' => 'CompanyType', 'menu_id' => 9, 'show_in_menu' => 'yes'],
            ['id' => 29, 'name' => 'Edit organization type', 'slug' => 'settings.company-types.edit', 'description' => null, 'model' => 'CompanyType', 'menu_id' => 9, 'show_in_menu' => 'no'],
            ['id' => 30, 'name' => 'Delete organization type', 'slug' => 'settings.company-types.delete', 'description' => null, 'model' => 'CompanyType', 'menu_id' => 9, 'show_in_menu' => 'no'],
            // User logs (menu_id 10)
            ['id' => 31, 'name' => 'View user logs', 'slug' => 'settings.user_logs.view', 'description' => null, 'model' => 'UserLog', 'menu_id' => 10, 'show_in_menu' => 'yes'],
            // Settings (menu_id 11)
            ['id' => 32, 'name' => 'Edit settings', 'slug' => 'settings.settings.edit', 'description' => null, 'model' => 'Setting', 'menu_id' => 11, 'show_in_menu' => 'yes'],
            // Money Control (menu_id 12)
            ['id' => 33, 'name' => 'View dashboard', 'slug' => 'money.dashboard.view', 'description' => null, 'model' => 'Dashboard', 'menu_id' => 12, 'show_in_menu' => 'yes'],
            ['id' => 34, 'name' => 'View branches', 'slug' => 'money.branches.view', 'description' => null, 'model' => 'Branch', 'menu_id' => 12, 'show_in_menu' => 'yes'],
            ['id' => 35, 'name' => 'View shifts', 'slug' => 'money.shifts.view', 'description' => null, 'model' => 'Shift', 'menu_id' => 12, 'show_in_menu' => 'yes'],
            ['id' => 36, 'name' => 'View reconciliations', 'slug' => 'money.reconciliations.view', 'description' => null, 'model' => 'Reconciliation', 'menu_id' => 12, 'show_in_menu' => 'yes'],
            ['id' => 37, 'name' => 'Manage business setup', 'slug' => 'money.business.manage', 'description' => null, 'model' => 'Business', 'menu_id' => 12, 'show_in_menu' => 'yes'],
        ];

        foreach ($permissions as $p) {
            DB::table('permissions')->updateOrInsert(
                ['id' => $p['id']],
                array_merge($p, ['created_at' => $now, 'updated_at' => $now, 'deleted_at' => null])
            );
        }
    }
}
