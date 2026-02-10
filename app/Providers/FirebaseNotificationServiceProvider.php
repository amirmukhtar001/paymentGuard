<?php

namespace App\Providers;

use App\Services\FirebaseNotificationService;
use Illuminate\Support\ServiceProvider;

class FirebaseNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(FirebaseNotificationService::class, function ($app) {
            return new FirebaseNotificationService();
        });

        $this->app->alias(FirebaseNotificationService::class, 'firebase.notifications');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
