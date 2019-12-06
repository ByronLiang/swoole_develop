<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // 组合处理
        Broadcast::routes(['middleware' => ['api', 'auth:api,admin']]);
        // jwt 权限配置广播授权
        // Broadcast::routes(['middleware' => ['api', 'auth:admin']]);
        // api
        // Broadcast::routes(['middleware' => ['api', 'auth:api']]);

        require base_path('routes/channels.php');
    }
}
