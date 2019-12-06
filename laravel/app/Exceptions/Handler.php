<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param Exception $exception
     *
     * @return mixed|void
     *
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Exception                $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson() || 'api' == request()->segment(1)) {
            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                return \Response::error('请登录后继续', 401);
            }

            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                return \Response::error(array_first(array_first($exception->errors())), 422, $exception->errors());
            }

            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return \Response::error('Gone 数据不存在', 410);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return \Response::error('Not Found 地址不存在', 404);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                return \Response::error($exception->getMessage(), $exception->getStatusCode() ?: 500);
            }

            return $this->prepareJsonResponse($request, $this->prepareException($exception));
        }

        return parent::render($request, $exception);
    }

    /**
     * 未登录响应判断.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        $path = 'admin' == \Request::segment(1) ? '/admin' : '/';

        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest($path);
    }
}
