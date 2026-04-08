<?php

namespace Isotope\Metronic;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Isotope\Metronic\Http\Middlewares\LocaleMiddleware;
use Isotope\Metronic\Http\Middlewares\AuthorizationMiddleware;

class MetronicServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::aliasMiddleware('authorization', AuthorizationMiddleware::class);
        $this->app['router']->pushMiddlewareToGroup('web', LocaleMiddleware::class);
        
        Paginator::useBootstrapFive();

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
        $this->loadViewsFrom(__DIR__ . '/Resources', 'isotope');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'isotope');

        $this->publishes([
            __DIR__ . '/../stubs' => public_path('isotope/metronic'),
        ]);
        
        Gate::before(function ($user, $ability) {
            return $user->isSuperAdmin() ? true : $user->hasPermission($ability);
        });
        
        foreach (glob(__DIR__ . '/Http/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/sidebar.php', 'sidebar.metronic');
        $this->mergeConfigFrom(__DIR__ . '/Config/ipermissions.php', 'ipermissions');

        $system = include __DIR__.'/Config/system.php';

        $config = $this->app->make('config');
        $config->set('filesystems.disks.ftp', $system['ftp']);
    }
}
