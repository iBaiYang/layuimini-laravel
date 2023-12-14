# layuimini-laravel

## 说明

结合 layuimini 和 laravel 6.20 框架开发的一个可以快速进行Admin后台开发的框架。

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

## 数据准备

数据表信息：
```
CREATE TABLE `ll_admin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL COMMENT '账号',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `mobile` varchar(13) NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱',
  `remark` text COMMENT '备注',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '账号状态：1正常 2禁用',
  `created_at` int NOT NULL COMMENT '创建时间',
  `updated_at` int NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='管理员信息表';

INSERT INTO `ll_admin` (`id`, `username`, `password`, `mobile`, `email`, `remark`, `status`, `created_at`, `updated_at`) 
VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '', 'admin', '1', '1702438466', '1702438466');

CREATE TABLE `ll_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父节点，0表示根节点',
  `type` tinyint(1) DEFAULT '0' COMMENT '类别: 1目录 2菜单 3操作',
  `title` varchar(50) NOT NULL COMMENT '节点名称',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `target` varchar(20) NOT NULL DEFAULT '_self',
  `href` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '账号状态：1正常 2禁用 3删除',
  `created_at` int NOT NULL COMMENT '创建时间',
  `updated_at` int NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='操作信息表';

CREATE TABLE `ll_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '角色名',
  `action_ids` text COMMENT '操作id',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '账号状态：1正常 2禁用 3删除',
  `created_at` int NOT NULL COMMENT '创建时间',
  `updated_at` int NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色信息表';

CREATE TABLE `ll_admin_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL COMMENT '管理员id',
  `role_id` int(11) unsigned NOT NULL COMMENT '角色id',
  `created_at` int NOT NULL COMMENT '创建时间',
  `updated_at` int NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='管理员-角色表';
```

管理员admin的密码：admin







## 参考

LAYUI MINI 官网：http://layuimini.99php.cn/

Laravel 官网：https://laravel.com/

Layui 官网： https://layui.dev/

Laravel 6 中文文档：https://laravelacademy.org/books/laravel-docs-6

