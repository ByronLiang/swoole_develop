# 模块系统

## 快速入门
1. xxxx 代指要使用的模块
1. `composer require {复制 Modules/xxxx/composer.json 下 name}:dev`

## 建立自己的模块
以下 `xxx` 代指模块名

1. 在 `Modules` 下建立文件夹 `xxx`
2. 在终端打开 `Modules/xxx` 执行 `composer init --name=ganguo/xxx` ,  然后一路回车
3. 再执行 `composer config version dev`
4. 回到上上层目录 `laravel` 下执行 composer require ganguo/xxx:dev
5. 然后可以在 `xxx` 下开发， 其余参考其他模块

## 打日志规范

- 统一 `__METHOD__.'内容', $array`
- 某个方法被多次调用是，后面追加 `debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)` 
- 尽可能用 info 或者 debug，尽可能少用 error， 避免造成日志混乱

```php
\Log::info(__METHOD__.':'.$msg, $array);

\Log::info(__METHOD__.':'.$msg, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5));

\Log::info(__METHOD__.':'.$msg, [$array, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)]);
```
