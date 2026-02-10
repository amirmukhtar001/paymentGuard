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
            ['route' => 'settings.menus.list', 'menu_id' => 2, 'permission_id' => 1],
            ['route' => 'settings.menus.create', 'menu_id' => 2, 'permission_id' => 2],
            ['route' => 'settings.menus.store', 'menu_id' => 2, 'permission_id' => 2],
            ['route' => 'settings.menus.edit', 'menu_id' => 2, 'permission_id' => 3],
            ['route' => 'settings.menus.update', 'menu_id' => 2, 'permission_id' => 3],
            ['route' => 'settings.menus.delete', 'menu_id' => 2, 'permission_id' => 4],
            ['route' => 'settings.my-permissions.list', 'menu_id' => 3, 'permission_id' => 5],
            ['route' => 'settings.my-permissions.create', 'menu_id' => 3, 'permission_id' => 6],
            ['route' => 'settings.my-permissions.store', 'menu_id' => 3, 'permission_id' => 6],
            ['route' => 'settings.my-permissions.edit', 'menu_id' => 3, 'permission_id' => 7],
            ['route' => 'settings.my-permissions.update', 'menu_id' => 3, 'permission_id' => 7],
            ['route' => 'settings.my-permissions.delete', 'menu_id' => 3, 'permission_id' => 8],
            ['route' => 'settings.my-roles.list', 'menu_id' => 4, 'permission_id' => 9],
            ['route' => 'settings.my-roles.create', 'menu_id' => 4, 'permission_id' => 10],
            ['route' => 'settings.my-roles.store', 'menu_id' => 4, 'permission_id' => 10],
            ['route' => 'settings.my-roles.edit', 'menu_id' => 4, 'permission_id' => 11],
            ['route' => 'settings.my-roles.update', 'menu_id' => 4, 'permission_id' => 11],
            ['route' => 'settings.my-roles.delete', 'menu_id' => 4, 'permission_id' => 12],
            ['route' => 'settings.my-roles.show', 'menu_id' => 4, 'permission_id' => 13],
            ['route' => 'settings.my-roles.role-permissions-save', 'menu_id' => 4, 'permission_id' => 13],
            ['route' => 'settings.companies.list', 'menu_id' => 5, 'permission_id' => 14],
            ['route' => 'settings.companies.create', 'menu_id' => 5, 'permission_id' => 15],
            ['route' => 'settings.companies.store', 'menu_id' => 5, 'permission_id' => 15],
            ['route' => 'settings.companies.edit', 'menu_id' => 5, 'permission_id' => 16],
            ['route' => 'settings.companies.update', 'menu_id' => 5, 'permission_id' => 16],
            ['route' => 'settings.companies.delete', 'menu_id' => 5, 'permission_id' => 17],
            ['route' => 'settings.sections.list', 'menu_id' => 7, 'permission_id' => 18],
            ['route' => 'settings.sections.create', 'menu_id' => 7, 'permission_id' => 19],
            ['route' => 'settings.sections.store', 'menu_id' => 7, 'permission_id' => 19],
            ['route' => 'settings.sections.edit', 'menu_id' => 7, 'permission_id' => 20],
            ['route' => 'settings.sections.update', 'menu_id' => 7, 'permission_id' => 20],
            ['route' => 'settings.sections.delete', 'menu_id' => 7, 'permission_id' => 21],
            ['route' => 'settings.users-mgt.list', 'menu_id' => 8, 'permission_id' => 22],
            ['route' => 'settings.users-mgt.create', 'menu_id' => 8, 'permission_id' => 23],
            ['route' => 'settings.users-mgt.store', 'menu_id' => 8, 'permission_id' => 23],
            ['route' => 'settings.users-mgt.edit', 'menu_id' => 8, 'permission_id' => 24],
            ['route' => 'settings.users-mgt.update', 'menu_id' => 8, 'permission_id' => 24],
            ['route' => 'settings.users-mgt.delete', 'menu_id' => 8, 'permission_id' => 25],
            ['route' => 'settings.users-mgt.show', 'menu_id' => 8, 'permission_id' => 26],
            ['route' => 'settings.users-mgt.user-permissions-save', 'menu_id' => 8, 'permission_id' => 26],
            ['route' => 'settings.company-types.list', 'menu_id' => 9, 'permission_id' => 27],
            ['route' => 'settings.company-types.create', 'menu_id' => 9, 'permission_id' => 28],
            ['route' => 'settings.company-types.store', 'menu_id' => 9, 'permission_id' => 28],
            ['route' => 'settings.company-types.edit', 'menu_id' => 9, 'permission_id' => 29],
            ['route' => 'settings.company-types.update', 'menu_id' => 9, 'permission_id' => 29],
            ['route' => 'settings.company-types.delete', 'menu_id' => 9, 'permission_id' => 30],
            ['route' => 'settings.user_logs.index', 'menu_id' => 10, 'permission_id' => 31],
            ['route' => 'settings.user_logs.list', 'menu_id' => 10, 'permission_id' => 31],
            ['route' => 'settings.settings', 'menu_id' => 11, 'permission_id' => 32],
            ['route' => 'settings.save', 'menu_id' => 11, 'permission_id' => 32],
        ];

        foreach ($routes as $i => $row) {
            DB::table('permission_routes')->updateOrInsert(
                ['route' => $row['route'], 'menu_id' => $row['menu_id']],
                [
                    'is_default' => 'yes',
                    'title' => $row['route'],
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
