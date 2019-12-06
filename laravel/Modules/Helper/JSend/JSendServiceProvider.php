<?php

namespace Modules\Helper\JSend;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class JSendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        Response::macro('success', function ($value = null) {
            return (new JSend())->success($value);
        });
        Response::macro('error', function ($errorMessage = null, $errorCode = null, $data = null) {
            return (new JSend())->error($errorMessage, $errorCode, $data);
        });
    }
}
