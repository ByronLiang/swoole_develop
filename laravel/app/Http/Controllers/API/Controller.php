<?php

namespace App\Http\Controllers\API;

/**
 * @OA\Info(version="1.0.0",title="Dreamer OpenApi",
 *      description="in the URL for parameter ?api_token=XXX.<br/>in the header for 'Authorization: Bearer XXX'. Which is used in JWT, Oauth, etc.",
 * )
 * @OA\Server(url="/api"),
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT",
 * )
 */
abstract class Controller extends \App\Http\Controllers\Controller
{
    /**
     * @var \App\Models\User|null
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
