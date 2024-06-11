<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/layuimini/lib/layui-v2.6.3/css/layui.css" media="all">
    <link rel="stylesheet" href="/layuimini/css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">

        <fieldset class="table-search-fieldset">
            <legend>搜索信息</legend>
            <div style="margin: 10px 10px 10px 10px">
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">角色名</label>
                            <div class="layui-input-inline">
                                <input type="text" name="name" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">状态</label>
                            <div class="layui-input-inline">
                                <select name="status">
                                    <option value="">不限</option>
                                    <option value="1">正常</option>
                                    <option value="2">禁用</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">添加时间</label>
                            <div class="layui-input-inline" style="width: 300px;">
                                <input type="text" name="created_at" id="created_at" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button type="submit" class="layui-btn layui-btn-primary" lay-submit
                                    lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </fieldset>

        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">
                <button class="layui-btn layui-btn-normal layui-btn-sm data-add-btn" lay-event="add"> 添加</button>
                <button class="layui-btn layui-btn-sm layui-btn-danger data-delete-btn" lay-event="delete"> 删除</button>
            </div>
        </script>

        <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>

        <script type="text/html" id="currentTableBar">
            <a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="action">权限分配</a>
            <a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="user">授权用户</a>
            <a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete">删除</a>
        </script>

    </div>
</div>
<script src="/layuimini/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'table', 'laydate'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.table,
            laydate = layui.laydate;

        table.render({
            elem: '#currentTableId',
            url: '{{route('admin_rbac_role_api')}}',
            toolbar: '#toolbarDemo',
            defaultToolbar: ['filter', 'exports', 'print', {
                title: '提示',
                layEvent: 'LAYTABLE_TIPS',
                icon: 'layui-icon-tips'
            }],
            cols: [[
                {type: "checkbox", width: 50, fixed: 'left'},
                {field: 'id', width: 80, title: 'ID', sort: true, fixed: 'left'},
                {field: 'name', minWidth: 80, title: '角色名', fixed: 'left'},
                {field: 'sort', width: 80, title: '排序', fixed: 'left'},
                {
                    field: 'status', title: '状态', align: 'center', templet: function (d) {
                        return d.status == 1 ? '正常' : '<bold style="color:red">禁用</bold>';
                    }
                },
                {
                    field: 'created_at', title: '添加时间', minWidth: 100, templet: function (d) {
                        var created_at = new Date(parseInt(d.created_at) * 1000);
                        return created_at.toLocaleDateString().replace(/\//g, "-") + " " + created_at.toTimeString().substr(0, 8);
                    }
                },
                {
                    field: 'updated_at', title: '更新时间', minWidth: 100, templet: function (d) {
                        var updated_at = new Date(parseInt(d.updated_at) * 1000);
                        return updated_at.toLocaleDateString().replace(/\//g, "-") + " " + updated_at.toTimeString().substr(0, 8);
                    }
                },
                {title: '操作', minWidth: 300, toolbar: '#currentTableBar', align: "center", fixed: 'right'}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 15,
            page: true,
            skin: 'line'
        });

        // 添加日期
        laydate.render({
            elem: '#created_at'
            , type: 'datetime'
            , range: '~'
        });

        // 监听搜索操作
        form.on('submit(data-search-btn)', function (data) {
            var result = JSON.stringify(data.field);
            // layer.alert(result, {
            //     title: '最终的搜索信息'
            // });

            //执行搜索重载
            table.reload('currentTableId', {
                page: {
                    curr: 1
                }
                , where: {
                    searchParams: result
                }
            }, 'data');

            return false;
        });

        /**
         * toolbar监听事件
         */
        table.on('toolbar(currentTableFilter)', function (obj) {
            if (obj.event === 'add') {  // 监听添加操作
                var index = layer.open({
                    title: '添加角色',
                    type: 2,
                    shade: 0.2,
                    maxmin: true,
                    shadeClose: true,
                    // area: ['100%', '100%'],
                    // area: ['50%', '70%'],
                    area: ['500px','350px'],
                    content: '{{route('admin_rbac_role_edit')}}',
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
            } else if (obj.event === 'delete') {  // 监听删除操作
                layer.confirm('真的删除这些角色么', function () {
                    var checkStatus = table.checkStatus('currentTableId')
                        , data = checkStatus.data;
                    // layer.alert(JSON.stringify(data));
                    var ids = new Array();
                    data.forEach(function (one) {
                        ids.push(one.id);
                    });

                    $.post('{{route('admin_rbac_role_delete')}}', {
                        ids: JSON.stringify(ids),
                        _token: "{{csrf_token()}}"
                    }, function (ret) {
                        ret = JSON.parse(ret);
                        if (ret.code) {
                            layer.alert(ret.msg);
                            return false;
                        }

                        layer.alert(ret.msg);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    });
                });
            }
        });

        //监听表格复选框选择
        table.on('checkbox(currentTableFilter)', function (obj) {
            // console.log(obj)
        });

        table.on('tool(currentTableFilter)', function (obj) {
            var data = obj.data;
            if (obj.event === 'edit') {
                var index = layer.open({
                    title: '编辑角色',
                    type: 2,
                    shade: 0.2,
                    maxmin: true,
                    shadeClose: true,
                    // area: ['100%', '100%'],
                    area: ['500px', '350px'],
                    content: '{{route('admin_rbac_role_edit')}}' + '?id=' + data.id,
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
                return false;
            } else if (obj.event === 'delete') {
                layer.confirm('真的删除该角色么', function (index) {
                    var ids = new Array();
                    ids.push(data.id);

                    $.post('{{route('admin_rbac_role_delete')}}', {
                        ids: JSON.stringify(ids),
                        _token: "{{csrf_token()}}"
                    }, function (ret) {
                        ret = JSON.parse(ret);
                        if (ret.code) {
                            layer.alert(ret.msg);
                            return false;
                        }

                        obj.del();
                        layer.close(index);

                        layer.alert(ret.msg);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    });
                });
            } else if (obj.event === 'action') {
                var index = layer.open({
                    title: '权限分配',
                    type: 2,
                    shade: 0.2,
                    maxmin: true,
                    shadeClose: true,
                    area: ['800px', '600px'],
                    content: '{{route('admin_rbac_role_actions')}}' + '?id=' + data.id,
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
                return false;
            } else if (obj.event === 'user') {
                var index = layer.open({
                    title: '权限分配',
                    type: 2,
                    shade: 0.2,
                    maxmin: true,
                    shadeClose: true,
                    area: ['600px', '500px'],
                    content: '{{route('admin_rbac_role_users')}}' + '?id=' + data.id,
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
                return false;
            }
        });

    });
</script>

</body>
</html>
