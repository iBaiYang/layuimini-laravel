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
<div class="layui-form layuimini-form">
    @csrf
    <div class="layui-form-item">
        <label class="layui-form-label required">角色名</label>
        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" lay-reqtext="角色名不能为空" placeholder="请输入角色名"
                   value="{{$record['name'] ?? ''}}" class="layui-input">
            <tip>填写角色名！</tip>
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
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="checkbox" name="status" lay-skin="switch" lay-text="正常|禁用"
                   @if(empty($record) || $record['status'] == 1) checked="checked" @endif>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="saveBtn">确认保存</button>
        </div>
    </div>
</div>
<script src="/layuimini/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.$;

        //监听提交
        form.on('submit(saveBtn)', function (data) {
            var id = '{{$record['id']??''}}';
            $.post('{{route('admin_rbac_role_edit')}}?id=' + id, $.parseJSON(JSON.stringify(data.field)), function (ret) {
                ret = JSON.parse(ret);
                if (ret.code) {
                    layer.alert(ret.msg);
                    return false;
                }

                layer.alert(ret.msg);
                setTimeout(function () {
                    var iframeIndex = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(iframeIndex);
                    parent.location.reload();
                }, 1000);
            });
            return false;
        });

    });
</script>
</body>
</html>
