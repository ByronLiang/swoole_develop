<?php

namespace App\Http\Requests\API;

/**
 *  @OA\Schema(type="object",required={"account"},
 *      @OA\Property(property="account",type="string",description="用户手机号码"),
 *  )
 */
class SupplierCaptchaRequest extends Request
{
    public function rules()
    {
        return [
            'account' => 'required|digits:11',
        ];
    }

    public function message()
    {
        return [
            'captcha' => '验证码错误',
        ];
    }
}
