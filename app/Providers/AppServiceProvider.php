<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Apply dynamic storage configuration
        try {
            app(\App\Services\StorageConfigService::class)->apply();
        } catch (\Exception $e) {
            // Silently fail if settings table not yet migrated
        }
    }
}
