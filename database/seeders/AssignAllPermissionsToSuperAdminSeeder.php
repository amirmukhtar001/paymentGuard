<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignAllPermissionsToSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder ensures ALL permissions are assigned to Super Admin role (id = 1)
     * It will skip permissions that are already assigned to avoid duplicates.
     *
     * @return void
     */
    public function run()
    {
        // Get Super Admin and Admin role IDs
        $roleIds = [1, 2]; // 1 = Super Admin, 2 = Admin

        // Get all permissions
        $allPermissions = DB::table('permissions')
            ->whereNull('deleted_at')
            ->pluck('id');

        foreach ($roleIds as $roleId) {
            // Get permissions already assigned to this role
            $assignedPermissions = DB::table('permission_role')
                ->where('role_id', $roleId)
                ->whereNull('deleted_at')
                ->pluck('permission_id')
                ->toArray();

            // Get permissions that need to be assigned
            $unassignedPermissions = $allPermissions->diff($assignedPermissions);

            // Assign all unassigned permissions to this role
            $insertData = [];
            foreach ($unassignedPermissions as $permissionId) {
                // Check if this permission-role combination already exists
                $exists = DB::table('permission_role')
                    ->where('role_id', $roleId)
                    ->where('permission_id', $permissionId)
                    ->exists();

                if (!$exists) {
                    $insertData[] = [
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'deleted_at' => null,
                    ];
                }
            }

            // Insert in batches if there are permissions to assign
            if (!empty($insertData)) {
                DB::table('permission_role')->insert($insertData);
                $roleName = $roleId == 1 ? 'Super Admin' : 'Admin';
                $this->command->info('Assigned ' . count($insertData) . ' permission(s) to ' . $roleName . ' role.');
            } else {
                $roleName = $roleId == 1 ? 'Super Admin' : 'Admin';
                $this->command->info('All permissions are already assigned to ' . $roleName . ' role.');
            }
        }
    }
}
