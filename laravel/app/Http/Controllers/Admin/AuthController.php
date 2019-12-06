<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\LoginRequest;
use Modules\Helper\ImageCaptcha;

class AuthController extends Controller
{
    public function postLogin(LoginRequest $request)
    {
        if (auth()->attempt($request->extractInputFromRules(), $request->remember)) {
            return \Response::success(auth()->user());
        }
        (new ImageCaptcha())->store();

        return \Response::error('账号或者密码错误');
    }

    public function getLogin()
    {
        return \Response::success((new ImageCaptcha())->url());
    }

    public function postJwtLogin(LoginRequest $request)
    {
        $request_data = $request->extractInputFromRules();
        $token = auth('admin')->attempt($request_data);
        if ($token) {
            $user = auth()->user();
            $user->makeVisible('api_token');
            $user->api_token = $token;

            return \Response::success($user);
        } else {
            (new ImageCaptcha())->store();

            return \Response::error('密码不正确', 422);
        }
    }
}
