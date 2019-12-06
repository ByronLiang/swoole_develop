<?php

namespace App\Http\Requests\Admin;

use App\Rules\CaptchaRule;
use Modules\Helper\ImageCaptcha;

class LoginRequest extends Request
{
    public function rules()
    {
        return [
            'account' => 'required',
            'password' => 'required|min:3|max:200',
            'captcha' => [(new ImageCaptcha())->has() ? 'required' : '', new CaptchaRule(new ImageCaptcha())],
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
