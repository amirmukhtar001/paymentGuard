<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $this->call(MenusTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(AssignAllPermissionsToSuperAdminSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(PermissionRoutesTableSeeder::class);
        $this->call(PermissionUserTableSeeder::class);
        $this->call(CompanyTypesTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);

        Model::reguard();
    }
}
