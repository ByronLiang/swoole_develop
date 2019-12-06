<?php

namespace Modules\TagGable\Providers;

use Illuminate\Support\ServiceProvider;

class TagGableServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }
}
