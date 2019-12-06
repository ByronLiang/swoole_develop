<?php

namespace App\Providers;

use Modules\Wechat\Events\ScanEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ScanEvent::class => [
            \App\Listeners\ScanSubScribeListener::class,
            \App\Listeners\ScanUnSubScribeListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    }
}
