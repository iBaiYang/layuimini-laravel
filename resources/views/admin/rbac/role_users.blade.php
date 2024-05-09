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
        <div id="box"></div>
    </div>
</div>
<script src="/layuimini/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'table','transfer'], function () {
        var form = layui.form,
            layer = layui.layer,
            transfer = layui.transfer,
            $ = layui.$;
        transfer.render({
            elem: '#box'  //绑定元素
            ,data: JSON.parse(JSON.stringify(@json($users)))
            ,value: JSON.parse(JSON.stringify(@json($role_users)))
            ,id: 'demo1' //定义索引
            ,title: ['所有用户','授权用户'] //定义索引
            , onchange: function (data, index) {
                var type = index ? 'leave' : 'join';
                var ids = '';
                $.each(data, function (key, val) {
                    ids += val.value + ',';
                })
                var id = '{{$record['id']??''}}';
                $.post('{{route('admin_rbac_role_users')}}?id=' + id, {
                    ids: ids,
                    type: type,
                    _token: '{{csrf_token()}}'
                }, function (ret) {
                    ret = JSON.parse(ret);
                    if (ret.code) {
                        layer.alert(ret.msg);
                        return false;
                    }
                });
                return false;
            }
        });

    });
</script>
</body>
</html>
