<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\SupplierCaptchaRequest;

class SupplierController extends Controller
{
    /**
     * @OA\Post(path="/supplier/captcha",tags={"第三方供应"},summary="获取验证码",description="",
     *     @OA\Response(response=200,description="successful operation"),
     *     @OA\RequestBody(required=true,description="",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Items(ref="#/components/schemas/SupplierCaptchaRequest")
     *         )
     *     ),
     * )
     */
    public function postCaptcha(SupplierCaptchaRequest $request)
    {
        (new \Modules\Sms\Captcha())->send($request->account);

        return \Response::success();
    }

    public function getSwooleObject()
    {
        // 获取容器Swoole Server对象
        $swoole = app('swoole');

        return \Response::success($swoole->getClientList());
    }
}
