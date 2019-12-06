# 聚合支付，支付宝&微信
#### 注：你可以对里面的方法进行完善，但是严禁往里面丢业务逻辑相关代码

## 启用模块
```
php artisan module:enable AggregationPay
```

## 如何使用？

### 在你的需要支付的模型里面添加下面这两个引入
```php
use Modules\AggregationPay\AggregationPayInterface

class XXXXXX extends Model implements AggregationPayInterface {
    use \Modules\AggregationPay\AggregationPayTrait;
}
```

### 添加通知回调路由 routes/callback.php

```php
\Modules\AggregationPay\AggregationPay::routesHook();
```

### 在`app\Providers\EventServiceProvider.php`添加事件监听

```php
   protected $listen = [
        \Modules\AggregationPay\Events\AggregationPayEvent::class => [
        ],
    ];
```

#### 注： 无论是支付成功还是退款成功都会调上面的方法，所以请参考下面的例子写 Listener

```php
namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AggregationPay\Entities\PaymentRecord;
use Modules\AggregationPay\Entities\RefundRecord;
use Modules\AggregationPay\Events\AggregationPayEvent;

class XxxxxListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 回调处理
     * @param AggregationPayEvent $event
     */
    public function handle(AggregationPayEvent $event)
    {
        if($event->isPayment()){
            // 支付成功
        }
        
        if($event->isRefund()){
            // 退款成功
        }
    }
}
```
