<?php

namespace Jiny\Help\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class JinyHelpServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load package views
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'jiny-help');

        // Load package migrations
        $this->loadMigrationsFrom(__DIR__.'/../../databases/migrations');

        // Load package routes
        $this->loadRoutes();

        // Publish configuration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/help.php' => config_path('help.php'),
            ], 'jiny-help-config');

            // Publish views
            $this->publishes([
                __DIR__.'/../../resources/views' => resource_path('views/vendor/jiny-help'),
            ], 'jiny-help-views');

            // Publish migrations
            $this->publishes([
                __DIR__.'/../../databases/migrations' => database_path('migrations'),
            ], 'jiny-help-migrations');
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(__DIR__.'/../../config/help.php', 'help');
    }

    /**
     * Load package routes
     */
    protected function loadRoutes(): void
    {
        // Load admin routes with admin middleware and prefix
        Route::middleware(['web', 'admin'])
            ->prefix('admin/cms')
            ->group(__DIR__.'/../../routes/admin.php');

        // Load web routes
        Route::middleware(['web'])
            ->group(__DIR__.'/../../routes/web.php');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}