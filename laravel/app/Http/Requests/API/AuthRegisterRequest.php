<?php

namespace App\Http\Requests\API;

/**
 *  @OA\Schema(type="object",required={"nickname","account","captcha","password"},
 *      @OA\Property(property="nickname",type="string",description="昵称"),
 *      @OA\Property(property="account",type="string",description="手机号"),
 *      @OA\Property(property="captcha",type="string",description="验证码"),
 *      @OA\Property(property="password",type="string",description="密码"),
 *  )
 */
class AuthRegisterRequest extends Request
{
    public function rules()
    {
        $this->exception = ['captcha', 'sale_id'];

        return [
            // 'nickname' => 'required|unique:users,nickname',
            'account' => 'required|digits:11|unique:users,account',
            // 'captcha' => ['required', new \Modules\Sms\Rules\Captcha($this->account)],
            // 'password' => ['required', new \App\Rules\Password()],
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'account.required' => '手机号不能为空',
            'account.unique' => '手机号已注册',
            // 'captcha.required' => '验证码不能为空',
            'password.required' => '密码不能为空',
        ];
    }
}
