<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsCategoryContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // Categories
            [
                'id' => 647,
                'name' => 'Can view categories',
                'slug' => 'settings.categories.view',
                'description' => null,
                'model' => 'Category',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 340,
                'show_in_menu' => 'yes',
            ],
            [
                'id' => 648,
                'name' => 'Can create categories',
                'slug' => 'settings.categories.create',
                'description' => null,
                'model' => 'Category',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 340,
                'show_in_menu' => 'yes',
            ],
            [
                'id' => 649,
                'name' => 'Can edit categories',
                'slug' => 'settings.categories.edit',
                'description' => null,
                'model' => 'Category',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 340,
                'show_in_menu' => 'no',
            ],
            [
                'id' => 650,
                'name' => 'Can delete categories',
                'slug' => 'settings.categories.delete',
                'description' => null,
                'model' => 'Category',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 340,
                'show_in_menu' => 'no',
            ],
            [
                'id' => 651,
                'name' => 'Can view category details',
                'slug' => 'settings.categories.show',
                'description' => null,
                'model' => 'Category',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 340,
                'show_in_menu' => 'no',
            ],
            // Contacts
            [
                'id' => 652,
                'name' => 'Can view contacts',
                'slug' => 'settings.contacts.view',
                'description' => null,
                'model' => 'Contact',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 341,
                'show_in_menu' => 'yes',
            ],
            [
                'id' => 653,
                'name' => 'Can create contacts',
                'slug' => 'settings.contacts.create',
                'description' => null,
                'model' => 'Contact',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 341,
                'show_in_menu' => 'yes',
            ],
            [
                'id' => 654,
                'name' => 'Can edit contacts',
                'slug' => 'settings.contacts.edit',
                'description' => null,
                'model' => 'Contact',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 341,
                'show_in_menu' => 'no',
            ],
            [
                'id' => 655,
                'name' => 'Can delete contacts',
                'slug' => 'settings.contacts.delete',
                'description' => null,
                'model' => 'Contact',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 341,
                'show_in_menu' => 'no',
            ],
            [
                'id' => 656,
                'name' => 'Can view contact details',
                'slug' => 'settings.contacts.show',
                'description' => null,
                'model' => 'Contact',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'menu_id' => 341,
                'show_in_menu' => 'no',
            ],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['id' => $permission['id']],
                $permission
            );
        }
    }
}
