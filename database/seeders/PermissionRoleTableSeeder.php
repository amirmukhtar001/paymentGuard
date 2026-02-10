<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Assigns base permissions to Super Admin (role 1).
     * AssignAllPermissionsToSuperAdminSeeder will assign any remaining permissions.
     */
    public function run(): void
    {
        $now = now();
        $permissionIds = DB::table('permissions')->whereNull('deleted_at')->pluck('id');
        $existing = DB::table('permission_role')
            ->where('role_id', 1)
            ->whereNull('deleted_at')
            ->pluck('permission_id')
            ->flip();

        foreach ($permissionIds as $permissionId) {
            if ($existing->has($permissionId)) {
                continue;
            }
            DB::table('permission_role')->insert([
                'permission_id' => $permissionId,
                'role_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);
        }
    }
}
