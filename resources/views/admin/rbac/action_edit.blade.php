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
    <style>
        body {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
<div class="layuimini-main">
    <div class="layui-form layuimini-form">
        @csrf
        <div class="layui-form-item">
            <label class="layui-form-label required">父节点</label>
            <div class="layui-input-block">
                <select name="pid">
                    <option value="0">根节点</option>
                    @foreach($menus as $menu)
                        <option
                            {{(!empty($record['pid']) && $record['pid'] == $menu['id']) ? 'selected':'' }} value="{{$menu['id']}}">{{$menu['title']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">类型</label>
            <div class="layui-input-block">
                <input type="radio" name="type" value="2" title="菜单" {{(!empty($record['type']) && $record['type'] == 2) ? 'checked' : ''}}>
                <input type="radio" name="type" value="3" title="操作" {{(!empty($record['type']) && $record['type'] == 3) || empty($record['type']) ? 'checked' : ''}}>
                <input type="radio" name="type" value="1" title="目录" {{(!empty($record['type']) && $record['type'] == 1) ? 'checked' : ''}}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">节点名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="required" lay-reqtext="节点名称为空" placeholder="请输入节点名称"
                       value="{{$record['title']??''}}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input type="number" name="sort" placeholder="请输入排序数字" value="{{$record['sort'] ?? 0}}"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">icon</label>
            <div class="layui-input-block">
                <input type="text" name="icon" placeholder="请输入icon" value="{{$record['icon'] ?? ''}}" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">target</label>
            <div class="layui-input-block">
                <input type="text" name="target" placeholder="请输入target" value="{{$record['target'] ?? '_self'}}"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">命名路由</label>
            <div class="layui-input-block">
                <input type="text" name="href" placeholder="请输入命名路由" value="{{$record['href'] ?? ''}}"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">状态</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="正常" {{(!empty($record['menu']) && $record['menu'] == 1) || empty($record['menu']) ? 'checked' : ''}}>
                <input type="radio" name="status" value="2" title="禁用" {{(!empty($record['menu']) && $record['menu'] == 2) ? 'checked' : ''}}>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="saveBtn">确认保存</button>
            </div>
        </div>
    </div>
</div>
<script src="/layuimini/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'table'], function () {
        var form = layui.form,
            layer = layui.layer,
            table = layui.table,
            $ = layui.$;

        //初始化表单，要加上，不然刷新部分组件可能会不加载
        form.render();

        // 当前弹出层，防止ID被覆盖
        var parentIndex = layer.index;

        //监听提交
        form.on('submit(saveBtn)', function (data) {
            var id = '{{$record['id'] ?? ''}}';
            $.post('{{route('admin_rbac_action_edit')}}?id=' + id, $.parseJSON(JSON.stringify(data.field)), function (ret) {
                ret = JSON.parse(ret);
                if (!ret.code) {
                    layer.alert(ret.msg);
                    return false;
                }

                layer.alert(ret.msg);
                setTimeout(function () {
                    var iframeIndex = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(iframeIndex);
                    parent.location.reload();
                }, 500);
            });
            return false;
        });

    });
</script>
</body>
</html>
