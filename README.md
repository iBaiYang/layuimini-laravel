# layuimini-laravel

## 说明

结合 layuimini 和 laravel 6.20 框架开发的一个可以快速进行Admin后台开发的框架。
master分支 依据的是 LAYUI MINI 的iframe版，v2_danye分支 依据的是 LAYUI MINI 的单页版。

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

如果项目权限部分报错：页面中checkAuth方法未找到，说明 helpers.php 文件未加载进来，执行下面的命令更新项目autoload文件：
> composer dump-autoload

## 使用

Laravel 本身是一个前后端结合的框架，我们可以在框架中进行前端视图开发。
Layuimini 本身是一个前端框架，我们要把 Layuimini 结合到 Laravel 中去，
这里可以采用页面样式复制的方式把Layuimini部分代码复制到Laravel视图页中去进行定制开发。

关于Layuimini页面样式，我们可以给 public/layuimini 文件夹搭建一个 html 服务，
然后在浏览器中查看效果、追溯源码，进行需要的代码的复制。

**注意**：不要为了在Laravel中展示Layuimini的个别页面而修改 public/layuimini 文件夹下的文件，
这样会导致自己搭建的layuimini的html服务无法使用。

## 数据准备

数据表信息：
```
-- ----------------------------
-- Table structure
-- ----------------------------
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
  `target` varchar(20) NOT NULL DEFAULT '_self当前窗口，_blank新窗口',
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
  `sort` int NOT NULL DEFAULT '0' COMMENT '排序',
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='管理员-角色表';


-- ----------------------------
-- Records of ll_admin
-- ----------------------------
INSERT INTO `ll_admin` VALUES ('2', 'test01', 'e10adc3949ba59abbe56e057f20f883e', '18405070001', 'test01@test.com', 'test01', '1', '1715064845', '1715312365');

-- ----------------------------
-- Records of ll_action
-- ----------------------------
INSERT INTO `ll_action` VALUES ('1', '0', '1', '内部管理', 'fa fa-gears', '_self', '', '3', '1', '1715064322', '1715318894');
INSERT INTO `ll_action` VALUES ('2', '1', '2', '组织管理', 'fa fa-shield', '_self', '', '0', '1', '1715064601', '1715242496');
INSERT INTO `ll_action` VALUES ('3', '2', '2', '员工管理', 'fa fa-stumbleupon-circle', '_self', 'admin_admin', '0', '1', '1715065006', '1715242543');
INSERT INTO `ll_action` VALUES ('4', '3', '3', '查看', '', '_self', 'admin_admin_api', '0', '1', '1715066285', '1715066285');
INSERT INTO `ll_action` VALUES ('5', '3', '3', '添加/编辑', '', '_self', 'admin_admin_edit', '0', '1', '1715066319', '1715066319');
INSERT INTO `ll_action` VALUES ('6', '3', '3', '删除', '', '_self', 'admin_admin_delete', '0', '1', '1715066337', '1715066337');
INSERT INTO `ll_action` VALUES ('7', '0', '1', '系统管理', 'fa fa-gears', '_self', '', '2', '1', '1715066477', '1715312993');
INSERT INTO `ll_action` VALUES ('8', '7', '2', '权限管理', 'fa fa-shield', '_self', '', '0', '1', '1715066503', '1715242598');
INSERT INTO `ll_action` VALUES ('9', '8', '2', '操作管理', 'fa fa-stumbleupon-circle', '_self', 'admin_rbac_action', '1', '1', '1715066536', '1715568836');
INSERT INTO `ll_action` VALUES ('10', '9', '3', '查看', '', '_self', 'admin_rbac_action_api', '0', '1', '1715066614', '1715066614');
INSERT INTO `ll_action` VALUES ('11', '9', '3', '添加/编辑', '', '_self', 'admin_rbac_action_edit', '0', '1', '1715066649', '1715066649');
INSERT INTO `ll_action` VALUES ('12', '0', '3', '功能操作', '', '_self', '', '1', '1', '1715312894', '1715312966');
INSERT INTO `ll_action` VALUES ('13', '17', '3', '首页', '', '_self', 'admin_home', '0', '1', '1715313855', '1715571404');
INSERT INTO `ll_action` VALUES ('14', '17', '3', '登出', '', '_self', 'admin_login_out', '0', '1', '1715313947', '1715571449');
INSERT INTO `ll_action` VALUES ('15', '17', '3', '菜单', '', '_self', 'admin_init', '0', '1', '1715313961', '1715571464');
INSERT INTO `ll_action` VALUES ('16', '17', '3', '清楚缓存', '', '_self', 'admin_clear', '0', '1', '1715313999', '1715322054');
INSERT INTO `ll_action` VALUES ('17', '12', '3', '基础操作', '', '_self', '', '0', '1', '1715314223', '1715314244');
INSERT INTO `ll_action` VALUES ('18', '8', '2', '角色管理', 'fa fa-viacoin', '_self', 'admin_rbac_role', '2', '1', '1715568546', '1715568844');
INSERT INTO `ll_action` VALUES ('19', '18', '3', '查看', '', '_self', 'admin_rbac_role_api', '0', '1', '1715568638', '1715568638');
INSERT INTO `ll_action` VALUES ('20', '18', '3', '添加/编辑', '', '_self', 'admin_rbac_role_edit', '0', '1', '1715568676', '1715568676');
INSERT INTO `ll_action` VALUES ('21', '9', '3', '删除', '', '_self', 'admin_rbac_action_delete', '0', '1', '1715573431', '1715573431');
INSERT INTO `ll_action` VALUES ('22', '18', '3', '删除', '', '_self', 'admin_rbac_role_delete', '0', '1', '1715573465', '1715573465');
INSERT INTO `ll_action` VALUES ('23', '18', '3', '权限分配', '', '_self', 'admin_rbac_role_actions', '0', '1', '1715573580', '1715573580');
INSERT INTO `ll_action` VALUES ('24', '18', '3', '授权用户', '', '_self', 'admin_rbac_role_users', '0', '1', '1715573605', '1715573605');

-- ----------------------------
-- Records of ll_role
-- ----------------------------
INSERT INTO `ll_role` VALUES ('1', '管理员', '13,14,15,16,10,11,21,19,20,22,23,24,4,5,6,2,3,8,9,12,17,18,1,7', '1', '1', '1715069792', '1715071470');
INSERT INTO `ll_role` VALUES ('2', '行政人员', '13,14,15,16,4,5,6,2,3,12,17,1', '3', '1', '1715070684', '1715572585');

-- ----------------------------
-- Records of ll_admin_role
-- ----------------------------
INSERT INTO `ll_admin_role` VALUES ('1', '2', '2', '1715572610');
```

管理员账号：admin，密码：admin。

测试账号：test01，密码：123456


## 参考

LAYUI MINI 官网：http://layuimini.99php.cn/

Laravel 官网：https://laravel.com/

Layui 官网： https://layui.dev/

Laravel 6 中文文档：https://laravelacademy.org/books/laravel-docs-6

