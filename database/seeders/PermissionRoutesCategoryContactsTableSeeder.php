<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoutesCategoryContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $routes = [
            // Categories
            [
                'is_default' => 'yes',
                'title' => 'All Categories',
                'description' => null,
                'route' => 'settings.categories.list',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 340,
                'permission_id' => 647,
            ],
            [
                'is_default' => 'no',
                'title' => 'Categories Datatable',
                'description' => null,
                'route' => 'settings.categories.datatable',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 340,
                'permission_id' => 647,
            ],
            [
                'is_default' => 'yes',
                'title' => 'New Category',
                'description' => null,
                'route' => 'settings.categories.create',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 340,
                'permission_id' => 648,
            ],
            [
                'is_default' => 'no',
                'title' => 'Store Category',
                'description' => null,
                'route' => 'settings.categories.store',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 340,
                'permission_id' => 648,
            ],
            [
                'is_default' => 'yes',
                'title' => 'Edit Category',
                'description' => null,
                'route' => 'settings.categories.edit',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 340,
                'permission_id' => 649,
            ],
            [
                'is_default' => 'no',
                'title' => 'Update Category',
                'description' => null,
                'route' => 'settings.categories.update',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 340,
                'permission_id' => 649,
            ],
            [
                'is_default' => 'yes',
                'title' => 'Delete Category',
                'description' => null,
                'route' => 'settings.categories.destroy',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 340,
                'permission_id' => 650,
            ],
            [
                'is_default' => 'yes',
                'title' => 'View Category Details',
                'description' => null,
                'route' => 'settings.categories.show',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 340,
                'permission_id' => 651,
            ],
            // Contacts
            [
                'is_default' => 'yes',
                'title' => 'All Contacts',
                'description' => null,
                'route' => 'settings.contacts.list',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 341,
                'permission_id' => 652,
            ],
            [
                'is_default' => 'no',
                'title' => 'Contacts Datatable',
                'description' => null,
                'route' => 'settings.contacts.datatable',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 341,
                'permission_id' => 652,
            ],
            [
                'is_default' => 'yes',
                'title' => 'New Contact',
                'description' => null,
                'route' => 'settings.contacts.create',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 341,
                'permission_id' => 653,
            ],
            [
                'is_default' => 'no',
                'title' => 'Store Contact',
                'description' => null,
                'route' => 'settings.contacts.store',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 341,
                'permission_id' => 653,
            ],
            [
                'is_default' => 'yes',
                'title' => 'Edit Contact',
                'description' => null,
                'route' => 'settings.contacts.edit',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 341,
                'permission_id' => 654,
            ],
            [
                'is_default' => 'no',
                'title' => 'Update Contact',
                'description' => null,
                'route' => 'settings.contacts.update',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 341,
                'permission_id' => 654,
            ],
            [
                'is_default' => 'yes',
                'title' => 'Delete Contact',
                'description' => null,
                'route' => 'settings.contacts.destroy',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 341,
                'permission_id' => 655,
            ],
            [
                'is_default' => 'yes',
                'title' => 'View Contact Details',
                'description' => null,
                'route' => 'settings.contacts.show',
                'created_at' => now(),
                'updated_at' => null,
                'deleted_at' => null,
                'menu_id' => 341,
                'permission_id' => 656,
            ],
        ];

        foreach ($routes as $route) {
            DB::table('permission_routes')->updateOrInsert(
                ['route' => $route['route']],
                $route
            );
        }
    }
}
