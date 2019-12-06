<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Helper\ImageCaptcha;

class CaptchaRule implements Rule
{
    private $adminCaptcha;

    /**
     * Create a new rule instance.
     */
    public function __construct(ImageCaptcha $adminCaptcha)
    {
        $this->adminCaptcha = $adminCaptcha;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->adminCaptcha->has()) {
            $check = captcha_check($value);
            if (!$check) {
                //如果不满足就马上启用验证码
                (new ImageCaptcha())->store();
            } else {
                // 满足使缓存标志失效
                (new ImageCaptcha())->remove();
            }
            \Log::info('passes:'.$value.' check:'.$check.' attribute:'.$attribute);

            return $check;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '图形验证码失败';
    }
}
