# 第三方授权登录

#### 注意
- 你可以对里面的方法进行完善，但是严禁往里面丢业务逻辑相关代码
- 为了后期获取 open_id 方便，微信登录：unique_id 不存储 union_id

### 给模型添加关系

在你的用户模型里里面添加下面两句use
```php
class XXXXXX extends Model implements \Modules\Socialite\SocialiteInterface {
    use \Modules\Socialite\SocialiteTrait;
}
```

