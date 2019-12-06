<?php

namespace App\Http\Requests\Admin;

class ProfileRequest extends Request
{
    public function rules()
    {
        return [
            'account' => 'required|unique:administrators,account,'.auth()->id(),
            'password' => 'confirmed',
        ];
    }

    public function messages()
    {
        return [
            'password.confirmed' => '新密码 与 确认新密码 不一致',
        ];
    }

    public function attributes()
    {
        return [
        ];
    }
}
