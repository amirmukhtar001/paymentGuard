<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Cache;

/**
 * Generic Policy for all models that uses permission-based authorization
 *
 * Usage:
 * - In AuthServiceProvider: Map models to this policy
 * - In Blade: @can('edit', $news) or @can('edit', [News::class, 'settings.news.edit'])
 */
class ModelPermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Permission mapping: Model class => permission prefix
     * Override in AuthServiceProvider or extend this policy
     */
    protected $permissionMap = [];

    /**
     * Determine if user can view any instances of the model
     */
    public function viewAny(User $user, string $modelClass = null): bool
    {
        if (!$modelClass) {
            return false;
        }

        $permissionPrefix = $this->getPermissionPrefix($modelClass);
        return $this->hasPermission($user, "{$permissionPrefix}.view");
    }

    /**
     * Determine if user can view the model
     */
    public function view(User $user, $model): bool
    {
        $modelClass = is_object($model) ? get_class($model) : $model;
        $permissionPrefix = $this->getPermissionPrefix($modelClass);

        // If model instance passed, extract permission from second parameter
        if (is_array($model) && isset($model[1])) {
            return $this->hasPermission($user, $model[1]);
        }

        return $this->hasPermission($user, "{$permissionPrefix}.show");
    }

    /**
     * Determine if user can create models
     */
    public function create(User $user, string $modelClass = null): bool
    {
        if (!$modelClass) {
            return false;
        }

        $permissionPrefix = $this->getPermissionPrefix($modelClass);
        return $this->hasPermission($user, "{$permissionPrefix}.create");
    }

    /**
     * Determine if user can update the model
     */
    public function update(User $user, $model): bool
    {
        $modelClass = is_object($model) ? get_class($model) : $model;
        $permissionPrefix = $this->getPermissionPrefix($modelClass);

        // If model instance passed, extract permission from second parameter
        if (is_array($model) && isset($model[1])) {
            return $this->hasPermission($user, $model[1]);
        }

        return $this->hasPermission($user, "{$permissionPrefix}.edit");
    }

    /**
     * Determine if user can delete the model
     */
    public function delete(User $user, $model): bool
    {
        $modelClass = is_object($model) ? get_class($model) : $model;
        $permissionPrefix = $this->getPermissionPrefix($modelClass);

        // If model instance passed, extract permission from second parameter
        if (is_array($model) && isset($model[1])) {
            return $this->hasPermission($user, $model[1]);
        }

        return $this->hasPermission($user, "{$permissionPrefix}.delete");
    }

    /**
     * Get permission prefix for a model class
     */
    protected function getPermissionPrefix(string $modelClass): string
    {
        // Check if custom mapping exists
        if (isset($this->permissionMap[$modelClass])) {
            return $this->permissionMap[$modelClass];
        }

        // Auto-generate from model class name
        // App\Models\News => settings.news
        $className = class_basename($modelClass);
        $snakeCase = \Illuminate\Support\Str::snake($className);
        $plural = \Illuminate\Support\Str::plural($snakeCase);

        return "settings.{$plural}";
    }

    /**
     * Check if user has permission directly or via role
     */
    protected function hasPermission(User $user, string $permissionSlug): bool
    {
        if (!$user) {
            return false;
        }

        // Get user's direct permissions
        $userPermissions = Cache::remember(
            "user_permissions_{$user->id}",
            3600,
            function () use ($user) {
                return $user->permissions()->pluck('slug')->toArray();
            }
        );

        // Get user's role permissions
        $rolePermissions = Cache::remember(
            "user_role_permissions_{$user->id}",
            3600,
            function () use ($user) {
                $permissions = collect();
                foreach ($user->roles as $role) {
                    $permissions = $permissions->merge(
                        $role->permissions()->pluck('slug')->toArray()
                    );
                }
                return $permissions->unique()->toArray();
            }
        );

        // Check if permission exists in either direct or role permissions
        return in_array($permissionSlug, $userPermissions)
            || in_array($permissionSlug, $rolePermissions);
    }
}
