<div class="layuimini-main">

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
            <label class="layui-form-label">手机</label>
            <div class="layui-input-block">
                <input type="text" name="mobile" placeholder="请输入手机" value="{{$record['mobile'] ?? ''}}" class="layui-input">
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
</div>
<script>
    layui.use(['form', 'table'], function () {
        var form = layui.form,
            layer = layui.layer,
            table = layui.table,
            $ = layui.$;

        /**
         * 初始化表单，要加上，不然刷新部分组件可能会不加载
         */
        form.render();

        // 当前弹出层，防止ID被覆盖
        var parentIndex = layer.index;

        //监听提交
        form.on('submit(saveBtn)', function (data) {
            // var index = layer.alert(JSON.stringify(data.field), {
            //     title: '最终的提交信息'
            // }, function () {
            //
            //     // 关闭弹出层
            //     layer.close(index);
            //     layer.close(parentIndex);
            //
            // });
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
                    parent.location.reload();
                },500);
            });
            return false;
        });

    });
</script>
