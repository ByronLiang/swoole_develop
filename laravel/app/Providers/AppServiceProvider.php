<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        \Illuminate\Database\Schema\Blueprint::macro('timestampsUi', function () {
            $this->unsignedInteger('created_at');
            $this->unsignedInteger('updated_at');
        });

        \Illuminate\Database\Schema\Blueprint::macro('softDeletesUi', function () {
            $this->unsignedInteger('deleted_at')->nullable();
        });
    }
}
