<?php

namespace Modules\MetaAble\Providers;

use Illuminate\Support\ServiceProvider;

class MetaAbleServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot()
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('meta_able.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'meta_able'
        );
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }
}
