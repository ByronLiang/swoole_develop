# 客户端聚合上传模块

#### 注：你可以对里面的方法进行完善，但是严禁往里面丢业务逻辑相关代码      

主要是客户端上传文件以及图片使用

## 如何使用？

### 无需添加控制器,直接添加通知回调路由 routes/admin.php 或者 routes/api.php
```php
Route::group(['prefix' => 'supplier'], function(){
    \Modules\ClientAggregationUpload\Factory::routesHook();
});
```
