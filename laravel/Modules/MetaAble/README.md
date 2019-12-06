# 扩展模型

本模块是基于 [laravel-metable](https://github.com/plank/laravel-metable) [docs](http://laravel-metable.readthedocs.io/en/latest/) ， 如有使用问题请自行查询文档

#### 注意
- 你可以对里面的方法进行完善，但是严禁往里面丢业务逻辑相关代码
- toArray() 只返回 key 和 value， 形式如下
```php
[
    "meta" => [
        "key" => "value"
    ]
]
```

## 给模型添加关系

在你的模型里里面添加下面这句
```php
class XXXXXX extends Model {
    use \Modules\MetaAble\MetaAbleTrait;
}
```

```php
$xxx = XXXXXX::find($id);
$xxx->setMeta($key, $value);
```
