<?php

namespace Modules\ActivityLog\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ActivityLog\ActivityLogger;
use Modules\ActivityLog\ActivityLogStatus;
use Modules\ActivityLog\Console\CleanActivityLogCommand;
use Modules\ActivityLog\Entities\ActivityLog;
use Modules\Activitylog\Exceptions\InvalidConfiguration;

class ActivityLogServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot()
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind('command.activitylog:clean', CleanActivityLogCommand::class);
        $this->commands([
            'command.activitylog:clean',
        ]);
        $this->app->bind(ActivityLogger::class);
        $this->app->singleton(ActivityLogStatus::class);
    }

    /**
     * Register config.
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('activitylog.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'activitylog'
        );
    }

    public static function determineActivityModel(): string
    {
        $activityModel = config('activitylog.activity_model') ?? ActivityLog::class;
        if (!is_a($activityModel, ActivityLog::class, true)) {
            throw InvalidConfiguration::modelIsNotValid($activityModel);
        }

        return $activityModel;
    }

    public static function getActivityModelInstance(): ActivityLog
    {
        $activityModelClassName = self::determineActivityModel();

        return new $activityModelClassName();
    }
}
