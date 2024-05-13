<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>menu</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/layuimini/lib/layui-v2.6.3/css/layui.css" media="all">
    <link rel="stylesheet" href="/layuimini/css/public.css" media="all">
    <style>
        .layui-btn:not(.layui-btn-lg ):not(.layui-btn-sm):not(.layui-btn-xs) {
            height: 34px;
            line-height: 34px;
            padding: 0 8px;
        }
        .status-green {
            font-size: 12px;
            color: #009688;
        }
        .status-orange {
            font-size: 12px;
            color: #FFB800;
        }
        .status-red {
            font-size: 12px;
            color: #FF5722;
        }
    </style>
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <div>
            <div class="layui-btn-group">
                <button class="layui-btn" id="btn-expand">全部展开</button>
                <button class="layui-btn layui-btn-normal" id="btn-fold">全部折叠</button>
                <button class="layui-btn layui-btn-warm" id="action-add">添加操作</button>
            </div>
            <table id="munu-table" class="layui-table" lay-filter="munu-table"></table>
        </div>
    </div>
</div>
<!-- 操作列 -->
<script type="text/html" id="auth-state">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script src="/layuimini/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script src="/layuimini/js/lay-config.js?v=1.0.4" charset="utf-8"></script>
<script>
    layui.use(['table', 'treetable'], function () {
        var $ = layui.jquery;
        var table = layui.table;
        var treetable = layui.treetable;

        // 渲染表格
        layer.load(2);
        treetable.render({
            treeColIndex: 1,
            treeSpid: 0,
            treeIdName: 'id',
            treePidName: 'pid',
            elem: '#munu-table',
            url: '{{route('admin_rbac_action_api')}}',
            page: false,
            cols: [[
                {type: 'numbers'},
                {field: 'title', minWidth: 200, title: '权限名称'},
                {field: 'target', title: '权限标识'},
                {field: 'href', title: '菜单url'},
                {field: 'sort', width: 80, align: 'center', title: '排序号'},
                {
                    field: 'isMenu', width: 80, align: 'center', templet: function (d) {
                        if (d.type == 3) {
                            return '<span class="layui-btn layui-btn-xs layui-btn-primary">按钮</span>';
                        } else if (d.type == 2) {
                            return '<span class="layui-btn layui-btn-xs layui-btn-primary layui-border-blue">菜单</span>';
                        } else if (d.type == 1) {
                            return '<span class="layui-btn layui-btn-xs layui-btn-normal">目录</span>';
                        }
                    }, title: '类型'
                },
                {
                    field: 'status', width: 80, align: 'center', templet: function (d) {
                        if (d.status == 1) {
                            return '<span class="status-green">正常</span>';
                        } else if (d.status == 2) {
                            return '<span class="status-orange">禁用</span>';
                        } else if (d.status == 3) {
                            return '<span class="status-red">删除</span>';
                        }
                    }, title: '状态'
                },
                {templet: '#auth-state', width: 120, align: 'center', title: '操作'}
            ]],
            done: function () {
                layer.closeAll('loading');
            }
        });

        $('#btn-expand').click(function () {
            treetable.expandAll('#munu-table');
        });

        $('#btn-fold').click(function () {
            treetable.foldAll('#munu-table');
        });

        $('#action-add').click(function () {
            var index = layer.open({
                title: '添加用户',
                type: 2,
                shade: 0.2,
                maxmin:true,
                shadeClose: true,
                area: ['500px','550px'],
                content: '{{route('admin_rbac_action_edit')}}',
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
        });

        //监听工具条
        table.on('tool(munu-table)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;

            if (layEvent === 'edit') {
                // layer.msg('修改' + data.id);
                layer.open({
                    title: '修改权限',
                    type: 2,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['500px','550px'],
                    content: '{{route('admin_rbac_action_edit')}}'+'?id=' + data.id,
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
                return false;
            } else if (layEvent === 'del') {
                // layer.msg('删除' + data.id);
                layer.confirm('真的删除行么', function (index) {
                    $.post('{{route('admin_rbac_action_delete')}}', {
                        id: data.id,
                        _token: "{{csrf_token()}}"
                    }, function (ret) {
                        ret = JSON.parse(ret);
                        if (ret.code) {
                            layer.alert(ret.msg);
                            return false;
                        }

                        layer.alert(ret.msg);
                        obj.del();
                        layer.close(index);
                    });



                });
                // layer.confirm('确定删除？',function () {
                //     if(!res.status){
                //         layer.alert(res.msg);
                //         return false;
                //     }
                //     layer.alert(res.msg);
                //     setTimeout(function () {
                //         window.location.reload();
                //     },500);
                // });
            }
        });
    });
</script>
</body>
</html>
