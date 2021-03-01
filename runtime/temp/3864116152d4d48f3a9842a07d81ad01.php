<?php /*a:1:{s:63:"D:\phpstudy_pro\WWW\jijin\application\index\view\jijin\add.html";i:1614595530;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.2</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        <link rel="stylesheet" href="/static/xadmin/css/font.css">
        <link rel="stylesheet" href="/static/xadmin/css/xadmin.css">
        <script type="text/javascript" src="/static/xadmin/lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="/static/xadmin/js/xadmin.js"></script>
        <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
        <!--[if lt IE 9]>
            <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
            <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]--></head>
    
    <body>
        <div class="layui-fluid">
            <div class="layui-row">
                <form class="layui-form">
                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label">
                            <span class="x-red">*</span>基金编码</label>
                        <div class="layui-input-inline">
                            <input type="text" id="code" name="code" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                    </div>
                    <div class="layui-form-item">
                        <label for="username" class="layui-form-label">
                            <span class="x-red">*</span>基金名称</label>
                        <div class="layui-input-inline">
                            <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                    </div>
                    <div class="layui-form-item">
                        <label for="phone" class="layui-form-label">
                            <span class="x-red">*</span>买入价格</label>
                        <div class="layui-input-inline">
                            <input type="text" id="buy_price" name="buy_price" required="" lay-verify="number" autocomplete="off" class="layui-input"></div>
                    </div>
                    <div class="layui-form-item">
                        <label for="phone" class="layui-form-label">
                            <span class="x-red">*</span>买入时间</label>
                        <div class="layui-input-inline">
                            <input type="text" id="buy_time" name="buy_time" required="" lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo htmlentities($timeNow); ?>"></div>
                    </div>

        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label"></label>
            <button class="layui-btn" lay-filter="add" lay-submit="">增加</button></div>
        </form>
        </div>
        </div>
        <script>layui.use(['form', 'layer'],
            function() {
                $ = layui.jquery;
                var form = layui.form,
                layer = layui.layer;

                //自定义验证规则
                // form.verify({
                //     nikename: function(value) {
                //         if (value.length < 5) {
                //             return '昵称至少得5个字符啊';
                //         }
                //     },
                //     pass: [/(.+){6,12}$/, '密码必须6到12位'],
                //     repass: function(value) {
                //         if ($('#L_pass').val() != $('#L_repass').val()) {
                //             return '两次密码不一致';
                //         }
                //     }
                // });

                //监听提交
                form.on('submit(add)',
                function(data) {
                    // console.log(data.field);
                    //发异步，把数据提交给php
                    $.ajax({
                        url:"<?php echo url('add'); ?>",
                        type:'post',
                        data:{code:data.field.code,name:data.field.name,buy_price:data.field.buy_price,buy_time:data.field.buy_time,},
                        // beforeSend:function () {
                        //     this.layerIndex = layer.load(0, { shade: [0.5, '#393D49'] });
                        // },
                        success:function(data){
                            console.log(data);
                            console.log(data.status);
                            console.log(data.msg);
                            if(data.status == 0){
                                // layer.msg(data.msg,{icon: 5});//失败的表情
                                // o.removeClass('layui-btn-disabled');
                                // return;
                                layer.alert(data.msg, {
                                        icon: 5
                                    },
                                    function() {
                                        // 获得frame索引
                                        var index = parent.layer.getFrameIndex(window.name);
                                        //关闭当前frame
                                        parent.layer.close(index);
                                    });
                                // return false;
                            }else{
                                layer.alert(data.msg, {
                                        icon: 6
                                    },
                                    function() {
                                        // 获得frame索引
                                        var index = parent.layer.getFrameIndex(window.name);
                                        //关闭当前frame
                                        parent.layer.close(index);
                                    });
                                // return false;
                            }
                        },
                        complete: function () {

                            var index = parent.layer.getFrameIndex(window.name);
                            setTimeout(function(){parent.layer.close(index)}, 1000);

                        },
                    });

                    layer.alert("测试", {
                        icon: 6
                    },
                    function() {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                    return false;
                });

            });</script>
        <script>
            layui.use('laydate', function(){
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#buy_time' //指定元素
                ,type: 'datetime'
            });
        });
        </script>
    </body>

</html>