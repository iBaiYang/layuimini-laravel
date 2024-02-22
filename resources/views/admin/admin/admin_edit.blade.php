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
        <label class="layui-form-label required">用户名</label>
        <div class="layui-input-block">
            <input type="text" name="username" lay-verify="required" lay-reqtext="用户名不能为空" placeholder="请输入用户名" value="{{$record['username'] ?? ''}}" class="layui-input">
            <tip>填写管理员的用户名！</tip>
        </div>
    </div>
    @if(empty($record))
        <div class="layui-form-item">
            <label class="layui-form-label required">密码</label>
            <div class="layui-input-block">
                <input type="password" name="password" lay-verify="required" lay-reqtext="密码为空" placeholder="请输入密码（字母、数字，6-12位）"  class="layui-input">
            </div>
        </div>
    @endif
    <div class="layui-form-item">
        <label class="layui-form-label required">手机</label>
        <div class="layui-input-block">
            <input type="number" name="mobile" lay-verify="required" lay-reqtext="手机不能为空" placeholder="请输入手机" value="{{$record['mobile'] ?? ''}}" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-block">
            <input type="email" name="email" placeholder="请输入邮箱" value="{{$record['email'] ?? ''}}" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">备注信息</label>
        <div class="layui-input-block">
            <textarea name="remark" class="layui-textarea" placeholder="请输入备注信息">{{$record['remark'] ?? ''}}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="checkbox" checked="checked" name="status" lay-skin="switch" lay-text="正常|禁用">
            <div class="layui-unselect layui-form-switch layui-form-onswitch" lay-skin="_switch"><em>正常</em><i></i></div>
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
            // var index = layer.alert(JSON.stringify(data.field), {
            //     title: '最终的提交信息'
            // }, function () {
            //
            //     // 关闭弹出层
            //     layer.close(index);
            //
            //     var iframeIndex = parent.layer.getFrameIndex(window.name);
            //     parent.layer.close(iframeIndex);
            //
            // });
            //
            // return false;

            var id = '{{$record['id']??''}}';
            $.post('{{route('admin_admin_edit')}}?id='+id, $.parseJSON(JSON.stringify(data.field)), function (ret) {
                ret = JSON.parse(ret);
                if(!ret.code){
                    layer.alert(ret.msg);
                    return false;
                }

                layer.alert(ret.msg);
                setTimeout(function () {
                    var iframeIndex = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(iframeIndex);
                },500);
            });
            return false;
        });

    });
</script>
</body>
</html>
