<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MyPermission;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Cache;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Check if user has a specific permission
     * This works with @can directive using permission slug
     *
     * Usage: @can('view', [App\Models\MyPermission::class, 'settings.news.edit'])
     * Or: @can('view', 'settings.news.edit')
     *
     * @param User $user
     * @param string|array $permission - Permission slug or [Model::class, 'permission.slug']
     * @return bool
     */
    public function view(User $user, $permission): bool
    {
        // Extract permission slug if array is passed
        $permissionSlug = is_array($permission) ? $permission[1] ?? $permission[0] : $permission;

        return $this->hasPermission($user, $permissionSlug);
    }

    /**
     * Alias for view - allows @can('check', 'permission.slug')
     */
    public function check(User $user, $permission): bool
    {
        return $this->view($user, $permission);
    }

    /**
     * Check if user has permission
     * Works with model actions: @can('edit', [News::class, 'settings.news.edit'])
     */
    public function edit(User $user, $permission): bool
    {
        return $this->view($user, $permission);
    }

    /**
     * Check if user has permission
     */
    public function create(User $user, $permission): bool
    {
        return $this->view($user, $permission);
    }

    /**
     * Check if user has permission
     */
    public function delete(User $user, $permission): bool
    {
        return $this->view($user, $permission);
    }

    /**
     * Check if user has permission
     */
    public function update(User $user, $permission): bool
    {
        return $this->view($user, $permission);
    }

    /**
     * Check if user has permission directly or via role
     *
     * @param User $user
     * @param string $permissionSlug
     * @return bool
     */
    protected function hasPermission(User $user, string $permissionSlug): bool
    {
        if (!$user) {
            return false;
        }

        // Get user's direct permissions (with fresh query if needed)
        // Check cache first, but allow bypass for immediate updates
        $cacheKey = "user_permissions_{$user->id}";
        $userPermissions = Cache::get($cacheKey);

        if ($userPermissions === null) {
            $userPermissions = $user->permissions()->pluck('slug')->toArray();
            Cache::put($cacheKey, $userPermissions, 3600);
        }

        // Get user's role permissions (with fresh query if needed)
        $roleCacheKey = "user_role_permissions_{$user->id}";
        $rolePermissions = Cache::get($roleCacheKey);

        if ($rolePermissions === null) {
            $permissions = collect();
            foreach ($user->roles as $role) {
                $permissions = $permissions->merge(
                    $role->permissions()->pluck('slug')->toArray()
                );
            }
            $rolePermissions = $permissions->unique()->toArray();
            Cache::put($roleCacheKey, $rolePermissions, 3600);
        }

        // Check if permission exists in either direct or role permissions
        return in_array($permissionSlug, $userPermissions)
            || in_array($permissionSlug, $rolePermissions);
    }
}
