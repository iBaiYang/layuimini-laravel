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

    @foreach($actions1 as $action1)

        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;">
            <legend>{{$action1['title']}}</legend>
        </fieldset>

        @if(!empty($action1['child']))
            @foreach($action1['child'] as $action1_child)
                <div style="margin-left: 20px; padding: 0 10px; font-size: 16px; font-weight: 300;">
                    {{$action1_child['title']}}
                </div>

                @if($action1_child['type'] == 3)
                    <div class="layui-form-item">
                            <div class="layui-input-block">
                            @foreach($actions2[$action1_child['id']]['child'] as $action2_child)
                                @if($action2_child['type'] == 3)
                                    <input type="checkbox" name="action_ids[]" @if(in_array($action2_child['id'], $action_ids)) checked @endif title="{{$action2_child['title']}}" value="{{$action2_child['id']}}">
                                @endif
                            @endforeach
                            </div>
                    </div>
                @else
                    @if(!empty($actions2[$action1_child['id']]['child']))
                        @foreach($actions2[$action1_child['id']]['child'] as $action2_child)
                            <div class="layui-form-item">
                                <label class="layui-form-label">{{$action2_child['title']}}</label>
                                <div class="layui-input-block">
                                    @if(!empty($actions3[$action2_child['id']]))
                                        @foreach($actions3[$action2_child['id']] as $action3)
                                            <input type="checkbox" name="action_ids[]" @if(in_array($action3['id'], $action_ids)) checked @endif title="{{$action3['title']}}" value="{{$action3['id']}}">
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endif
            @endforeach
        @endif

    @endforeach
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
            $.post('{{route('admin_rbac_role_actions')}}?id=' + id, $.parseJSON(JSON.stringify(data.field)), function (ret) {
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
