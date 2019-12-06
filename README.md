# 服务器要求

- [PHP](http://php.net/manual/zh/install.php) >= 7.1.0
    - OpenSSL PHP Extension
    - PDO PHP Extension
    - Mbstring PHP Extension
    - Tokenizer PHP Extension

- [Composer](https://getcomposer.org/)

- [MySQL](https://dev.mysql.com/doc/refman/5.7/en/installing.html) >= 5.7.8

- [NodeJS](https://nodejs.org/en/) >= 8

- [window 专用 Laragon](https://sourceforge.net/projects/laragon/)[使用文档](http://laravelacademy.org/post/7754.html)

# 开发必看
- 代码里,个别地方会有 README.nd 的文档,注意阅读
- [laravel 目录结构](https://laravel.com/docs/5.5/)
- [vuejs](https://vuejs.org/)
- [vue-router](https://router.vuejs.org/)

# 配置&安装

以下操作均在laravel目录下进行

## 目录权限

如果php-fpm进程和项目不是同一用户权限，storage和bootstrap目录需要写权限
- storage -> 666
- bootstrap/cache -> 666

## composer 使用

```sh
# 安装
composer install

# 更新
composer update
```

## env

```sh
# 复制一份环境变量
cp .env.example .env

# 生成应用key
php artisan key:generate
```

### 配置项

* `APP_ENV=local` 在正式环境删除此行

* `APP_DEBUG=true` 在正式环境删除此行

* `APP_URL=http://localhost` 在正式环境修改此行

### 数据库

- DB_DATABASE="数据库名"
- DB_USERNAME="数据库用户名"
- DB_PASSWORD="数据库密码"

#### 云存储
现已集成七牛,如需使用,请在如下配置

```env
QINIU_ACCESS_KEY=
QINIU_SECRET_KEY=
QINIU_DOMAIN=
QINIU_BUCKET=
QINIU_UPLOAD_URL=
```

#### 七牛总共有四个[存储区域](https://developer.qiniu.com/kodo/manual/1671/region-endpoint)的上传地址[QINIU_UPLOAD_URL](https://developer.qiniu.com/kodo/manual/1671/region-endpoint)


| 存储区域 | 地域简称 | 上传域名 |
|---|---|---|
| 华东 | z0 | 服务器端上传：http(s)://up.qiniup.com             客户端上传： http(s)://upload.qiniup.com |
| 华北 | z1 | 服务器端上传：http(s)://up-z1.qiniup.com          客户端上传：http(s)://upload-z1.qiniup.com |
| 华南 | z2 | 服务器端上传：http(s)://up-z2.qiniup.com          客户端上传：http(s)://upload-z2.qiniup.com |
| 北美 | na0 | 服务器端上传：http(s)://up-na0.qiniup.com        客户端上传：http(s)://upload-na0.qiniup.com |

# 运行

- `php artisan serve ` 或者 `php artisan serve --host=0.0.0.0:8000`

- 访问 {url}:8000/ 到首页

## 运行数据库操作命令

```sh
# 往数据库增加表以及填充数据
php artisan migrate --seed

# 重置数据库表结构以及填充数据
php artisan migrate:refresh --seed
```

## 运行前端开发环境命令

```sh
# 安装
npm i

# 开发模式编译
npm run dev

# 生产模式编译
npm run prod

# 实时编译
npm run watch

# 无刷新实时编译
npm run hot
```

## 系统依赖可选配置

### 定时任务执行

1. 在服务器上执行命令 `EDITOR=vim crontab -e`

2. 写入命令 `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1` 需要将路径替 换到artisan执行目录


### 数据库定时备份

1. 在服务器上执行命令 `EDITOR=vim crontab -e`

2. 写入命令 `0 */12 * * * mysqldump -u homestead -psecret homestead | gzip -c > ~/backup/mysql/homestead.$(date +"\%Y\%m\%dT\%H").sql.tar.gz` 需要将路径替 换到artisan执行目录


### 队列设置

1. 在root下安装supervisor：`apt install supervisor`

2. 将.supervisor.conf复制到/etc/supervisor/conf.d/下，请先确认文件中配置的项目路径和执行用户组都是对的
    - cp .supervisor.conf /etc/supervisor/conf.d/laravel-worker.conf

3. 在root下执行`supervisorctl update`

4. 检查进程`ps -ef|grep queue|grep -v grep`中是否有laravel queue队列进程

