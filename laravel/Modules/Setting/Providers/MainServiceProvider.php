<?php

namespace Modules\Setting\Providers;

use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    public function register()
    {
        $this->app->bind('Setting', \Modules\Setting\Entities\Setting::class);
        $this->app->bind(\Modules\Setting\Entities\SettingStorageContract::class, \Modules\Setting\Entities\EloquentStorage::class);
    }
}
