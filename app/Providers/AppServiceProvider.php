<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register Helper as a global alias for App\Helpers\Helpers
        $this->app->alias(\App\Helpers\Helpers::class, 'Helper');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(125);

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Add custom validation rule
        Validator::extend('alpha_spaces', function ($attribute, $value) {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        // Auto-detect APP_URL in production if not set or if it doesn't match request
        if ($this->app->environment('production') && !$this->app->runningInConsole()) {
            try {
                $request = request();
                if ($request && $request->getHttpHost()) {
                    $scheme = ($request->secure() || $request->server('HTTP_X_FORWARDED_PROTO') === 'https') ? 'https' : 'http';
                    $actualUrl = $scheme . '://' . $request->getHttpHost();
                    $configUrl = config('app.url');

                    // If APP_URL is not set or doesn't match the actual request URL, update it
                    if (empty($configUrl) || $configUrl === 'http://localhost' || $configUrl === 'http://127.0.0.1:8000') {
                        Config::set('app.url', $actualUrl);
                    }

                    // Force HTTPS scheme for URL generation if request is HTTPS
                    if ($scheme === 'https') {
                        URL::forceScheme('https');
                    }
                }
            } catch (\Exception $e) {
                // Ignore errors during boot
            }
        }

        // Apply mail settings from settings table
        $this->applyMailSettings();


    }

    /**
     * Apply mail configuration from settings table (only if not set in .env).
     * .env values take precedence over database settings.
     */
    private function applyMailSettings()
    {
        if (app()->runningInConsole()) {
            return;
        }

        try {
            // $mailDriver = setting('mail_driver');
            // $mailHost = setting('mail_host');
            // Use .env value if set, otherwise fall back to database setting
            $mailDriver = env('MAIL_MAILER') ?: setting('mail_driver');
            $mailHost = env('MAIL_HOST') ?: setting('mail_host');

            // If mail_host is null or empty, use 'log' driver to avoid DSN errors
            if (empty($mailHost)) {
                Config::set('mail.default', 'log');
                return;
            }

            // Only set mail config if host is provided
            if ($mailDriver) {
                Config::set('mail.default', $mailDriver);
            }

            Config::set('mail.mailers.smtp.host', $mailHost);

            $mailPort = env('MAIL_PORT') ?: setting('mail_port');
            if ($mailPort !== null) {
                Config::set('mail.mailers.smtp.port', $mailPort);
            }

            // Check if MAIL_ENCRYPTION is set in .env (even if empty string)
            $mailEncryption = env('MAIL_ENCRYPTION');
            if ($mailEncryption === null) {
                $mailEncryption = setting('mail_encryption');
            }
            if ($mailEncryption !== null) {
                Config::set('mail.mailers.smtp.encryption', $mailEncryption);
            }

            $mailUsername = env('MAIL_USERNAME') ?: setting('mail_username');
            if ($mailUsername !== null) {
                Config::set('mail.mailers.smtp.username', $mailUsername);
            }

            $mailPassword = env('MAIL_PASSWORD') ?: setting('mail_password');
            if ($mailPassword !== null) {
                Config::set('mail.mailers.smtp.password', $mailPassword);
            }

            $mailFromAddress = env('MAIL_FROM_ADDRESS') ?: setting('mail_from_address');
            if ($mailFromAddress !== null) {
                Config::set('mail.from.address', $mailFromAddress);
            }

            $mailFromName = env('MAIL_FROM_NAME') ?: setting('mail_from_name');
            if ($mailFromName !== null) {
                Config::set('mail.from.name', $mailFromName);
            }
        } catch (\Exception $e) {
            // If settings table doesn't exist or there's an error, use log driver
            Config::set('mail.default', 'log');
        }
    }
}
