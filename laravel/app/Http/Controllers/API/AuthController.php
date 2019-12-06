<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Requests\API\AuthLoginRequest;
use App\Http\Requests\API\AuthRegisterRequest;

class AuthController extends Controller
{
    /**
     * @OA\Post(path="/auth/register",tags={"认证"},summary="注册",description="",
     *     @OA\Response(response=200,description="successful operation",@OA\MediaType(mediaType="application/json")),
     *     @OA\RequestBody(required=true,description="",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Items(ref="#/components/schemas/AuthRegisterRequest")
     *         )
     *     ),
     * )
     */
    public function postRegister(AuthRegisterRequest $request)
    {
        $request_data = $request->extractInputFromRules();

        $user = new User();
        $user->showAndUpdateApiToken($request_data);

        return \Response::success($user);
    }

    /**
     * @OA\Post(path="/auth/login",tags={"认证"},summary="登陆",description="",
     *     @OA\Response(response=200,description="successful operation",@OA\MediaType(mediaType="application/json")),
     *     @OA\RequestBody(required=true,description="",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Items(ref="#/components/schemas/AuthLoginRequest")
     *         )
     *     ),
     * )
     */
    public function postLogin(AuthLoginRequest $request)
    {
        // 返回路由所有中间件名称, 筛选获取auth.driver的驱动名称
        // $middleware = $request->route()->middleware();
        $request_data = $request->extractInputFromRules();
        if (User::where('account', $request->account)->onlyTrashed()->first()) {
            return \Response::error('账号被冻结', 422);
        }
        // api_token 默认需要使用web的无状态登录
        if (!auth('web')->once($request_data)) {
            return \Response::error('密码不正确', 422);
        }
        $user = auth('web')->user();
        $user->showAndUpdateApiToken();

        return \Response::success($user);
    }

    // smaple in api use jwt_auth

    /**
     * @OA\Post(path="/auth/jwt_login",tags={"认证"},summary="JWT登陆",description="",
     *     @OA\Response(response=200,description="successful operation",@OA\MediaType(mediaType="application/json")),
     *     @OA\RequestBody(required=true,description="",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Items(ref="#/components/schemas/AuthLoginRequest")
     *         )
     *     ),
     * )
     */
    public function postJwtLogin(AuthLoginRequest $request)
    {
        $request_data = $request->extractInputFromRules();
        if (User::where('account', $request->account)->onlyTrashed()->first()) {
            return \Response::error('账号被冻结', 422);
        }
        $token = auth()->attempt($request_data);
        if ($token) {
            $user = auth()->user();
            $user->makeVisible('api_token');
            $user->api_token = $token;

            return \Response::success($user);
        } else {
            return \Response::error('密码不正确', 422);
        }
    }
}
