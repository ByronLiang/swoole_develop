<?php

namespace Modules\ReceivingPlace\Providers;

use Illuminate\Support\ServiceProvider;

class ReceivingPlaceServiceProvider extends ServiceProvider
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
