<?php

namespace Modules\AggregationPay\Providers;

use Illuminate\Support\ServiceProvider;

class AggregationPayServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
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
            __DIR__.'/../Config/config.php' => config_path('aggregation_pay.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'aggregation_pay'
        );
    }

    /**
     * Register translations.
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/AggregationPay');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'AggregationPay');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'AggregationPay');
        }
    }
}
