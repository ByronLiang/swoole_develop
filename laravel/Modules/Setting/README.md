# 设置操作
#### 注：你可以对里面的方法进行完善，但是严禁往里面丢业务逻辑相关代码

本模块来源[https://github.com/UniSharp/laravel-settings](https://github.com/UniSharp/laravel-settings)

## Usage

```php
\Setting::get('name', 'Computer');
// get setting value with key 'name'
// return 'Computer' if the key does not exists

\Setting::lang('zh-TW')->get('name', 'Computer');
// get setting value with key and language

\Setting::set('name', 'Computer');
// set setting value by key

\Setting::lang('zh-TW')->set('name', 'Computer');
// set setting value by key and language

\Setting::has('name');
// check the key exists, return boolean

\Setting::lang('zh-TW')->has('name');
// check the key exists by language, return boolean

\Setting::forget('name');
// delete the setting by key

\Setting::lang('zh-TW')->forget('name');
// delete the setting by key and language
```

## Dealing with array

```php
\Setting::get('item');
// return null;

\Setting::set('item', ['USB' => '8G', 'RAM' => '4G']);
\Setting::get('item');
// return array(
//     'USB' => '8G',
//     'RAM' => '4G',
// );

\Setting::get('item.USB');
// return '8G';
```

## Dealing with locale

By default language parameter are being resets every set or get calls. You could disable that and set your own long term language parameter forever using any route service provider or other method.

```php
\Setting::lang(App::getLocale())->langResetting(false);
```

## 
