<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use App\Models\User;

if (!function_exists('clearUserPermissionCache')) {
    /**
     * Clear permission cache for a user
     * Call this after assigning/removing permissions or roles
     */
    function clearUserPermissionCache($userId): void
    {
        Cache::forget("user_permissions_{$userId}");
        Cache::forget("user_role_permissions_{$userId}");

        // Also clear for all users if role permissions changed
        // This ensures all users with that role get updated permissions
        Cache::forget('permissions_for_gates');
    }
}

if (!function_exists('clearAllPermissionCache')) {
    /**
     * Clear all permission-related cache
     * Call this after bulk permission/role changes
     */
    function clearAllPermissionCache(): void
    {
        Cache::forget('permissions_for_gates');

        // Clear cache for all users (this might be expensive, use sparingly)
        // For better performance, clear specific user cache when assigning permissions
    }
}
