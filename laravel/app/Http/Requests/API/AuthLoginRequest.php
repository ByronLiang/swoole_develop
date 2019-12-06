<?php

namespace App\Http\Requests\API;

/**
 *  @OA\Schema(type="object",required={"account","password"},
 *      @OA\Property(property="account",type="string",description=""),
 *      @OA\Property(property="password",type="string",description=""),
 *  )
 */
class AuthLoginRequest extends Request
{
    public function rules()
    {
        return [
            'account' => 'required|exists:users,account',
            'password' => 'required|string|between:6,100',
        ];
    }

    public function messages()
    {
        return [
            'account.exists' => '手机号未注册',
        ];
    }
}
