# 省市区列表数据
#### 注：你可以对里面的方法进行完善，但是严禁往里面丢业务逻辑相关代码

## 添加seed文件

在 database\seeds\DatabaseSeeder 添加下面这句
```php
$this->call(\Modules\DistrictGable\Database\Seeders\DistrictGableDatabaseSeeder::class);
```

## 给模型添加关系

在你的模型里里面添加下面这句
```php
use \Modules\DistrictGable\DistrictGableTrait;
```