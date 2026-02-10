<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use App\Models\MyPermission;
use App\Policies\PermissionPolicy;
use App\Policies\ModelPermissionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Map PermissionPolicy for permission-based checks
        MyPermission::class => PermissionPolicy::class,

        // Map ModelPermissionPolicy for all models (optional - can map individually)
        // \App\Models\News::class => ModelPermissionPolicy::class,
        // \App\Models\Category::class => ModelPermissionPolicy::class,
        // \App\Models\Gallery::class => ModelPermissionPolicy::class,
        // \App\Models\Media::class => ModelPermissionPolicy::class,
        // \App\Models\Contact::class => ModelPermissionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Register dynamic Gates that delegate to PermissionPolicy
        // This allows @can('permission.slug') to work directly
        $this->registerPermissionGates();
    }

    /**
     * Register Gates dynamically based on permissions in database
     * These Gates delegate to PermissionPolicy for consistent authorization logic
     * This allows @can directive to work with your custom permission system
     */
    protected function registerPermissionGates()
    {
        try {
            // Get all permissions from cache or database
            $permissions = Cache::remember('permissions_for_gates', 3600, function () {
                return MyPermission::whereNull('deleted_at')
                    ->pluck('slug')
                    ->toArray();
            });

            $permissionPolicy = new PermissionPolicy();

            // Register a Gate for each permission that uses PermissionPolicy
            foreach ($permissions as $permissionSlug) {
                Gate::define($permissionSlug, function ($user) use ($permissionSlug, $permissionPolicy) {
                    // Delegate to PermissionPolicy for consistent authorization logic
                    return $permissionPolicy->view($user, $permissionSlug);
                });
            }
        } catch (\Exception $e) {
            // If database is not ready (during migrations), just skip
            // This prevents errors during fresh installations
        }
    }
}
