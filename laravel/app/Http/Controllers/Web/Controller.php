<?php

namespace App\Http\Controllers\Web;

abstract class Controller extends \App\Http\Controllers\Controller
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User|null
     */
    protected $my;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                $this->my = auth()->user();
            }

            return $next($request);
        });
    }
}
