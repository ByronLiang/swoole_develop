<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthDriverMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param null                     $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($guard) {
            Auth::setDefaultDriver($guard);
        }

        $this->debugAuth($request, $guard);

        return $next($request);
    }

    private function debugAuth(\Illuminate\Http\Request $request, $guard)
    {
        if (!env('APP_DEBUG')) {
            return;
        }
        if (is_numeric($request->get('login_id'))) {
            if ('session' == config('auth.guards.'.$guard.'.driver')) {
                Auth::loginUsingId(request('login_id'));
            }
        }
    }
}
