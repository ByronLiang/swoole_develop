<?php

namespace Modules\Wechat\Providers;

use Illuminate\Support\ServiceProvider;

class WechatProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     */
    public function boot()
    {
        $this->registerConfig();
        // $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }

    /**
     * Register config.
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('wechat.php'),
            __DIR__.'/../Config/wechat_template.php' => config_path('wechat_template.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'wechat'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../Config/wechat_template.php', 'wechat_template'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
