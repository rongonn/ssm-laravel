<?php

namespace SSM\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class SSMServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/sidebar.php', 'sidebar.ssm');
        
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'ssm');

        // Publishing assets
        $this->publishes([
            __DIR__ . '/../../resources/js/Pages' => resource_path('js/Pages/SSM'),
        ], 'ssm-pages');
    }
}
