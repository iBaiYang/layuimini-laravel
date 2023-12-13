# layuimini-laravel

## 说明

结合 layuimini 和 laravel 框架开发的一个可以快速进行Admin后台开发的框架。

## 安装

安装项目依赖包：
```
composer install --no-plugins --optimize-autoloader --no-dev
```

将 .env.example 文件复制为 .env 文件。

生成应用的密钥（APP_KEY）：
> php artisan key:generate

nginx伪静态配置：
```
    location / {
        index  index.html  index.htm  index.php;
        if (!-e $request_filename) {
               rewrite  ^(.*)$  /index.php?s=/$1  last;
               break;
         }
    }
```

## 使用

视图文件开发，给 public/layuimini 文件夹搭建 html 服务，在浏览器中查看效果、追溯源码进行开发。

## 参考

LAYUI MINI 官网：http://layuimini.99php.cn/

Laravel 官网：https://laravel.com/

Layui 官网： https://layui.dev/

Laravel 6 中文文档：https://laravelacademy.org/books/laravel-docs-6

