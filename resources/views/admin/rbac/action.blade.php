<style>
    .layui-btn:not(.layui-btn-lg ):not(.layui-btn-sm):not(.layui-btn-xs) {height:34px;line-height:34px;padding:0 8px;}
</style>
<body>
<div class="layuimini-container layuimini-page-anim">
    <div class="layuimini-main">
        <div>
            <div class="layui-btn-group">
                <button class="layui-btn" id="btn-expand">全部展开</button>
                <button class="layui-btn layui-btn-normal" id="btn-fold">全部折叠</button>
            </div>

            <script type="text/html" id="toolbarDemo">
                <div class="layui-btn-container">
                    <button class="layui-btn layui-btn-normal layui-btn-sm data-add-btn" lay-event="add"> 添加 </button>
                </div>
            </script>

            <table id="munu-table" class="layui-table" lay-filter="munu-table"></table>
        </div>
    </div>
</div>
<!-- 操作列 -->
<script type="text/html" id="auth-state">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
    layui.use(['table', 'treetable', 'miniPage'], function () {
        var $ = layui.jquery;
        var table = layui.table;
        var treetable = layui.treetable;
        var miniPage = layui.miniPage;

        // 渲染表格
        layer.load(2);
        treetable.render({
            treeColIndex: 1,
            treeSpid: -1,
            treeIdName: 'authorityId',
            treePidName: 'parentId',
            elem: '#munu-table',
            toolbar: '#toolbarDemo',
            url: '{{route('admin_menu_api')}}',
            page: false,
            cols: [[
                {type: 'numbers'},
                {field: 'authorityName', minWidth: 200, title: '权限名称'},
                {field: 'authority', title: '权限标识'},
                {field: 'menuUrl', title: '菜单url'},
                {field: 'orderNumber', width: 80, align: 'center', title: '排序号'},
                {
                    field: 'isMenu', width: 80, align: 'center', templet: function (d) {
                        if (d.isMenu == 1) {
                            return '<span class="layui-badge layui-bg-gray">按钮</span>';
                        }
                        if (d.parentId == -1) {
                            return '<span class="layui-badge layui-bg-blue">目录</span>';
                        } else {
                            return '<span class="layui-badge-rim">菜单</span>';
                        }
                    }, title: '类型'
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

        // 监听添加操作
        table.on('toolbar(munu-table)', function (obj) {
            if (obj.event === 'add') {
                var content = miniPage.getHrefContent('{{route('admin_rbac_action_edit')}}');
                var openWH = miniPage.getOpenWidthHeight();

                var index = layer.open({
                    title: '添加用户',
                    type: 1,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    // area: [openWH[0] + 'px', openWH[1] + 'px'],
                    area: ['500px','550px'],
                    // offset: [openWH[2] + 'px', openWH[3] + 'px'],
                    content: content,
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
            }
        });

        // 监听工具条
        table.on('tool(munu-table)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;

            if (layEvent === 'del') {
                // layer.msg('删除' + data.id);
                layer.confirm('真的删除行么', function (index) {
                    obj.del();
                    layer.close(index);
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
            } else if (layEvent === 'edit') {
                var content = miniPage.getHrefContent('{{route('admin_rbac_action_edit')}}'+'?id=' + data.id);
                layer.open({
                    title: '修改权限',
                    type: 1,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['500px','550px'],
                    content: content,
                });
            }
        });
    });
</script>
</body>
</html>
