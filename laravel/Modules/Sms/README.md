# 短信发送
#### 注：你可以对里面的方法进行完善，但是严禁往里面丢业务逻辑相关代码

1. 启用模块 `php artisan module:enable Sms`
2. 发布配置 `php artisan module:publish-config Sms`
3. 添加相应的逻辑代码

```php
// SupplierController
class SupplierController extends Controller
{
    public function postSmsCaptcha()
    {
        (new \Modules\Sms\Captcha())->send(request('phone'));
    }
}
```

```php
// xxxxRequest.php 验证

    public function rules()
    {
        $this->exception[] = 'captcha';
        return [
            'phone' => 'required|digits:11',
            'captcha' => ['required', new \Modules\Sms\Rules\Captcha($this->phone)],
        ];
    }
```



```php
$sms = new \Modules\Sms\Sms();

(new \Modules\Sms\Captcha())->send($phone);
(new \Modules\Sms\Captcha())->check($phone, $captcha);
```
