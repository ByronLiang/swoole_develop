<?php

namespace Modules\Sms\Rules;

use Illuminate\Contracts\Validation\Rule;

class Captcha implements Rule
{
    protected $phone;

    /**
     * Captcha constructor.
     *
     * @param $phone
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (new \Modules\Sms\Captcha())->check($this->phone, $value);
    }

    /**
     * @return string
     */
    public function message()
    {
        return ':attribute 错误';
    }
}
