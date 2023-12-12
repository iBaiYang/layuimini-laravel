<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers\Admin
 */
class HomeController extends CommonController
{
    /**
     * @param Request $request
     * @return false|string
     */
    public function login_out(Request $request)
    {
        try {
            $request->session()->forget('user_token');

            return $this->ret([
                'code' => 1,
                'msg' => '退出成功',
            ]);
        } catch (\Exception $ex) {
            return $this->ret(['msg' => $ex->getMessage()]);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        return view('admin.home.home');
    }

    /**
     * @return string
     */
    public function init()
    {
        $data = '
{
  "homeInfo": {
    "title": "首页",
    "href": "layuimini/page/welcome-1.html?t=1"
  },
  "logoInfo": {
    "title": "LAYUI MINI",
    "image": "layuimini/images/logo.png",
    "href": ""
  },
  "menuInfo": [
    {
      "title": "常规管理",
      "icon": "fa fa-address-book",
      "href": "",
      "target": "_self",
      "child": [
        {
          "title": "主页模板",
          "href": "",
          "icon": "fa fa-home",
          "target": "_self",
          "child": [
            {
              "title": "主页一",
              "href": "layuimini/page/welcome-1.html",
              "icon": "fa fa-tachometer",
              "target": "_self"
            },
            {
              "title": "主页二",
              "href": "layuimini/page/welcome-2.html",
              "icon": "fa fa-tachometer",
              "target": "_self"
            },
            {
              "title": "主页三",
              "href": "layuimini/page/welcome-3.html",
              "icon": "fa fa-tachometer",
              "target": "_self"
            }
          ]
        },
        {
          "title": "菜单管理",
          "href": "layuimini/page/menu.html",
          "icon": "fa fa-window-maximize",
          "target": "_self"
        },
        {
          "title": "系统设置",
          "href": "layuimini/page/setting.html",
          "icon": "fa fa-gears",
          "target": "_self"
        },
        {
          "title": "表格示例",
          "href": "layuimini/page/table.html",
          "icon": "fa fa-file-text",
          "target": "_self"
        },
        {
          "title": "表单示例",
          "href": "",
          "icon": "fa fa-calendar",
          "target": "_self",
          "child": [
            {
              "title": "普通表单",
              "href": "layuimini/page/form.html",
              "icon": "fa fa-list-alt",
              "target": "_self"
            },
            {
              "title": "分步表单",
              "href": "layuimini/page/form-step.html",
              "icon": "fa fa-navicon",
              "target": "_self"
            }
          ]
        },
        {
          "title": "登录模板",
          "href": "",
          "icon": "fa fa-flag-o",
          "target": "_self",
          "child": [
            {
              "title": "登录-1",
              "href": "layuimini/page/login-1.html",
              "icon": "fa fa-stumbleupon-circle",
              "target": "_blank"
            },
            {
              "title": "登录-2",
              "href": "layuimini/page/login-2.html",
              "icon": "fa fa-viacoin",
              "target": "_blank"
            },
            {
              "title": "登录-3",
              "href": "layuimini/page/login-3.html",
              "icon": "fa fa-tags",
              "target": "_blank"
            }
          ]
        },
        {
          "title": "异常页面",
          "href": "",
          "icon": "fa fa-home",
          "target": "_self",
          "child": [
            {
              "title": "404页面",
              "href": "layuimini/page/404.html",
              "icon": "fa fa-hourglass-end",
              "target": "_self"
            }
          ]
        },
        {
          "title": "其它界面",
          "href": "",
          "icon": "fa fa-snowflake-o",
          "target": "",
          "child": [
            {
              "title": "按钮示例",
              "href": "layuimini/page/button.html",
              "icon": "fa fa-snowflake-o",
              "target": "_self"
            },
            {
              "title": "弹出层",
              "href": "layuimini/page/layer.html",
              "icon": "fa fa-shield",
              "target": "_self"
            }
          ]
        }
      ]
    },
    {
      "title": "组件管理",
      "icon": "fa fa-lemon-o",
      "href": "",
      "target": "_self",
      "child": [
        {
          "title": "图标列表",
          "href": "layuimini/page/icon.html",
          "icon": "fa fa-dot-circle-o",
          "target": "_self"
        },
        {
          "title": "图标选择",
          "href": "layuimini/page/icon-picker.html",
          "icon": "fa fa-adn",
          "target": "_self"
        },
        {
          "title": "颜色选择",
          "href": "layuimini/page/color-select.html",
          "icon": "fa fa-dashboard",
          "target": "_self"
        },
        {
          "title": "下拉选择",
          "href": "layuimini/page/table-select.html",
          "icon": "fa fa-angle-double-down",
          "target": "_self"
        },
        {
          "title": "文件上传",
          "href": "layuimini/page/upload.html",
          "icon": "fa fa-arrow-up",
          "target": "_self"
        },
        {
          "title": "富文本编辑器",
          "href": "layuimini/page/editor.html",
          "icon": "fa fa-edit",
          "target": "_self"
        },
        {
          "title": "省市县区选择器",
          "href": "layuimini/page/area.html",
          "icon": "fa fa-rocket",
          "target": "_self"
        }
      ]
    },
    {
      "title": "其它管理",
      "icon": "fa fa-slideshare",
      "href": "",
      "target": "_self",
      "child": [
        {
          "title": "多级菜单",
          "href": "",
          "icon": "fa fa-meetup",
          "target": "",
          "child": [
            {
              "title": "按钮1",
              "href": "layuimini/page/button.html?v=1",
              "icon": "fa fa-calendar",
              "target": "_self",
              "child": [
                {
                  "title": "按钮2",
                  "href": "layuimini/page/button.html?v=2",
                  "icon": "fa fa-snowflake-o",
                  "target": "_self",
                  "child": [
                    {
                      "title": "按钮3",
                      "href": "layuimini/page/button.html?v=3",
                      "icon": "fa fa-snowflake-o",
                      "target": "_self"
                    },
                    {
                      "title": "表单4",
                      "href": "layuimini/page/form.html?v=1",
                      "icon": "fa fa-calendar",
                      "target": "_self"
                    }
                  ]
                }
              ]
            }
          ]
        },
        {
          "title": "失效菜单",
          "href": "layuimini/page/error.html",
          "icon": "fa fa-superpowers",
          "target": "_self"
        }
      ]
    }
  ]
}
        ';

        return $data;
    }

    public function clear()
    {
        return $this->ret([
            'code' => 1,
            'msg' => '服务端清理缓存成功',
        ]);
    }

    public function menu()
    {
        return $this->ret([
            'code' => 0,
            'msg' => '',
            'count' => 19,
            'data' => [
                [
                    "authorityId" =>  1,
                    "authorityName" =>  "系统管理",
                    "orderNumber" =>  1,
                    "menuUrl" => null,
                    "menuIcon" =>  "layui-icon-set",
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  null,
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 09:13:42",
                    "isMenu" =>  0,
                    "parentId" =>  -1
                ],
                [
                    "authorityId" =>  2,
                    "authorityName" =>  "用户管理",
                    "orderNumber" =>  2,
                    "menuUrl" =>  "system/user",
                    "menuIcon" =>  null,
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  null,
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 09:13:42",
                    "isMenu" =>  0,
                    "parentId" =>  1
                ],
                [
                    "authorityId" =>  3,
                    "authorityName" =>  "查询用户",
                    "orderNumber" =>  3,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/07/21 13:54:16",
                    "authority" =>  "user:view",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/21 13:54:16",
                    "isMenu" =>  1,
                    "parentId" =>  2
                ],
                [
                    "authorityId" =>  4,
                    "authorityName" =>  "添加用户",
                    "orderNumber" =>  4,
                    "menuUrl" =>  null,
                    "menuIcon" =>  null,
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  "user:add",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 09:13:42",
                    "isMenu" =>  1,
                    "parentId" =>  2
                ],
                [
                    "authorityId" =>  5,
                    "authorityName" =>  "修改用户",
                    "orderNumber" =>  5,
                    "menuUrl" =>  null,
                    "menuIcon" =>  null,
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  "user:edit",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 09:13:42",
                    "isMenu" =>  1,
                    "parentId" =>  2
                ],
                [
                    "authorityId" =>  6,
                    "authorityName" =>  "删除用户",
                    "orderNumber" =>  6,
                    "menuUrl" =>  null,
                    "menuIcon" =>  null,
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  "user:delete",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 09:13:42",
                    "isMenu" =>  1,
                    "parentId" =>  2
                ],
                [
                    "authorityId" =>  7,
                    "authorityName" =>  "角色管理",
                    "orderNumber" =>  7,
                    "menuUrl" =>  "system/role",
                    "menuIcon" =>  null,
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  null,
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 09:13:42",
                    "isMenu" =>  0,
                    "parentId" =>  1
                ],
                [
                    "authorityId" =>  8,
                    "authorityName" =>  "查询角色",
                    "orderNumber" =>  8,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/07/21 13:54:59",
                    "authority" =>  "role:view",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/21 13:54:58",
                    "isMenu" =>  1,
                    "parentId" =>  7
                ],
                [
                    "authorityId" =>  9,
                    "authorityName" =>  "添加角色",
                    "orderNumber" =>  9,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  "role:add",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 09:13:42",
                    "isMenu" =>  1,
                    "parentId" =>  7
                ],
                [
                    "authorityId" =>  10,
                    "authorityName" =>  "修改角色",
                    "orderNumber" =>  10,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  "role:edit",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 09:13:42",
                    "isMenu" =>  1,
                    "parentId" =>  7
                ],
                [
                    "authorityId" =>  11,
                    "authorityName" =>  "删除角色",
                    "orderNumber" =>  11,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  "role:delete",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 09:13:42",
                    "isMenu" =>  1,
                    "parentId" =>  7
                ],
                [
                    "authorityId" =>  12,
                    "authorityName" =>  "角色权限管理",
                    "orderNumber" =>  12,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  "role:auth",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 15:27:18",
                    "isMenu" =>  1,
                    "parentId" =>  7
                ],
                [
                    "authorityId" =>  13,
                    "authorityName" =>  "权限管理",
                    "orderNumber" =>  13,
                    "menuUrl" =>  "system/authorities",
                    "menuIcon" =>  null,
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  null,
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 15:45:13",
                    "isMenu" =>  0,
                    "parentId" =>  1
                ],
                [
                    "authorityId" =>  14,
                    "authorityName" =>  "查询权限",
                    "orderNumber" =>  14,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/07/21 13:55:57",
                    "authority" =>  "authorities:view",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/21 13:55:56",
                    "isMenu" =>  1,
                    "parentId" =>  13
                ],
                [
                    "authorityId" =>  15,
                    "authorityName" =>  "添加权限",
                    "orderNumber" =>  15,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  "authorities:add",
                    "checked" =>  0,
                    "updateTime" =>  "2018/06/29 11:05:41",
                    "isMenu" =>  1,
                    "parentId" =>  13
                ],
                [
                    "authorityId" =>  16,
                    "authorityName" =>  "修改权限",
                    "orderNumber" =>  16,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/07/13 09:13:42",
                    "authority" =>  "authorities => edit",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/13 09:13:42",
                    "isMenu" =>  1,
                    "parentId" =>  13
                ],
                [
                    "authorityId" =>  17,
                    "authorityName" =>  "删除权限",
                    "orderNumber" =>  17,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  "authorities:delete",
                    "checked" =>  0,
                    "updateTime" =>  "2018/06/29 11:05:41",
                    "isMenu" =>  1,
                    "parentId" =>  13
                ],
                [
                    "authorityId" =>  18,
                    "authorityName" =>  "登录日志",
                    "orderNumber" =>  18,
                    "menuUrl" =>  "system/loginRecord",
                    "menuIcon" =>  null,
                    "createTime" =>  "2018/06/29 11:05:41",
                    "authority" =>  null,
                    "checked" =>  0,
                    "updateTime" =>  "2018/06/29 11:05:41",
                    "isMenu" =>  0,
                    "parentId" =>  1
                ],
                [
                    "authorityId" =>  19,
                    "authorityName" =>  "查询登录日志",
                    "orderNumber" =>  19,
                    "menuUrl" =>  "",
                    "menuIcon" =>  "",
                    "createTime" =>  "2018/07/21 13:56:43",
                    "authority" =>  "loginRecord:view",
                    "checked" =>  0,
                    "updateTime" =>  "2018/07/21 13:56:43",
                    "isMenu" =>  1,
                    "parentId" =>  18
                ]
            ]
        ]);
    }

    public function table()
    {
        return $this->ret([
            'code' => 0,
            'msg' => '',
            'count' => 19,
            'data' => [
                [
                  "id" =>  10000,
                  "username" =>  "user-0",
                  "sex" =>  "女",
                  "city" =>  "城市-0",
                  "sign" =>  "签名-0",
                  "experience" =>  255,
                  "logins" =>  24,
                  "wealth" =>  82830700,
                  "classify" =>  "作家",
                  "score" =>  57
                ],
                [
                    "id" =>  10001,
                  "username" =>  "user-1",
                  "sex" =>  "男",
                  "city" =>  "城市-1",
                  "sign" =>  "签名-1",
                  "experience" =>  884,
                  "logins" =>  58,
                  "wealth" =>  64928690,
                  "classify" =>  "词人",
                  "score" =>  27
                ],
                [
                    "id" =>  10002,
                  "username" =>  "user-2",
                  "sex" =>  "女",
                  "city" =>  "城市-2",
                  "sign" =>  "签名-2",
                  "experience" =>  650,
                  "logins" =>  77,
                  "wealth" =>  6298078,
                  "classify" =>  "酱油",
                  "score" =>  31
                ],
                [
                    "id" =>  10003,
                  "username" =>  "user-3",
                  "sex" =>  "女",
                  "city" =>  "城市-3",
                  "sign" =>  "签名-3",
                  "experience" =>  362,
                  "logins" =>  157,
                  "wealth" =>  37117017,
                  "classify" =>  "诗人",
                  "score" =>  68
                ],
                [
                    "id" =>  10004,
                  "username" =>  "user-4",
                  "sex" =>  "男",
                  "city" =>  "城市-4",
                  "sign" =>  "签名-4",
                  "experience" =>  807,
                  "logins" =>  51,
                  "wealth" =>  76263262,
                  "classify" =>  "作家",
                  "score" =>  6
                ],
                [
                    "id" =>  10005,
                  "username" =>  "user-5",
                  "sex" =>  "女",
                  "city" =>  "城市-5",
                  "sign" =>  "签名-5",
                  "experience" =>  173,
                  "logins" =>  68,
                  "wealth" =>  60344147,
                  "classify" =>  "作家",
                  "score" =>  87
                ],
                [
                    "id" =>  10006,
                  "username" =>  "user-6",
                  "sex" =>  "女",
                  "city" =>  "城市-6",
                  "sign" =>  "签名-6",
                  "experience" =>  982,
                  "logins" =>  37,
                  "wealth" =>  57768166,
                  "classify" =>  "作家",
                  "score" =>  34
                ],
                [
                    "id" =>  10007,
                  "username" =>  "user-7",
                  "sex" =>  "男",
                  "city" =>  "城市-7",
                  "sign" =>  "签名-7",
                  "experience" =>  727,
                  "logins" =>  150,
                  "wealth" =>  82030578,
                  "classify" =>  "作家",
                  "score" =>  28
                ],
                [
                    "id" =>  10008,
                  "username" =>  "user-8",
                  "sex" =>  "男",
                  "city" =>  "城市-8",
                  "sign" =>  "签名-8",
                  "experience" =>  951,
                  "logins" =>  133,
                  "wealth" =>  16503371,
                  "classify" =>  "词人",
                  "score" =>  14
                ],
                [
                    "id" =>  10009,
                  "username" =>  "user-9",
                  "sex" =>  "女",
                  "city" =>  "城市-9",
                  "sign" =>  "签名-9",
                  "experience" =>  484,
                  "logins" =>  25,
                  "wealth" =>  86801934,
                  "classify" =>  "词人",
                  "score" =>  75
                ]
            ]
        ]);
    }

    public function tableSelect()
    {
        return $this->ret([
            'code' => 0,
            'msg' => '',
            'count' => 16,
            'data' => [
                ["id" => "001", "username" => "张玉林", "sex" => "女"],
                ["id" => "002", "username" => "刘晓军", "sex" => "男"],
                ["id" => "003", "username" => "张恒", "sex" => "男"],
                ["id" => "004", "username" => "朱一", "sex" => "男"],
                ["id" => "005", "username" => "刘佳能", "sex" => "女"],
                ["id" => "006", "username" => "晓梅", "sex" => "女"],
                ["id" => "007", "username" => "马冬梅", "sex" => "女"],
                ["id" => "008", "username" => "刘晓庆", "sex" => "女"],
                [ "id" => "009", "username" => "刘晓庆", "sex" => "女"],
                [ "id" => "010", "username" => "刘晓庆", "sex" => "女"],
                [ "id" => "011", "username" => "刘晓庆", "sex" => "女"],
                [ "id" => "012", "username" => "刘晓庆", "sex" => "女"],
                [ "id" => "013", "username" => "刘晓庆", "sex" => "女"],
                [ "id" => "014", "username" => "刘晓庆", "sex" => "女"],
                [ "id" => "015", "username" => "刘晓庆", "sex" => "女"],
                [ "id" => "016", "username" => "刘晓庆", "sex" => "女"]
            ]
        ]);
    }

    public function upload()
    {
        return $this->ret([
            'code' => 1,
            'msg' => '上传成功',
            'data' => [
                'url' => [
                    "../images/logo.png",
                    "../images/captcha.jpg"
                ]
            ]
        ]);
    }


}
