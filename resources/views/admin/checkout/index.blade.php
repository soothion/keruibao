@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>
                @can('produce.checkout.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.checkout.create') }}">添 加</a>
                @endcan
                <button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>
            </div>

            <div class="layui-form">
                
                <div class="layui-input-inline">
                    <select name="product_id" lay-verify="required" id="product_id">
                        <option value="">请选择产品</option>
                        @foreach($products as $product)
                            <option value="{{$product->id}}" @if(isset($checkout->product_id)&&$checkout->product_id==$product->id)selected @endif >{{$product->id}} - {{$product->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="layui-input-inline">
                    <select name="status" id="status">
                        <option value="">请选择状态</option>
                        <option value="未出库">未出库</option>
                        <option value="已出库">已出库</option>
                    </select>
                </div>

                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="date"  autocomplete="off" name="" placeholder="请选择日期范围"
                               style="width:300px;">
                    </div>
                </div>
            </div>

        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('produce.checkout.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('produce.checkout.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('produce.manage')
        <script type="text/html" id="toolbar">
            <h5>总金额:<span id="totalAmount">0</span>元</h5>
        </script>
        <script>
            layui.use(['layer','table','form', 'laydate'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                var laydate = layui.laydate;

                //执行一个laydate实例
                laydate.render({
                  elem: '#date', //指定元素
                  range: true
                });
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 700
                    ,url: "{{ route('admin.checkout.data') }}" //数据接口
                    ,page: true //开启分页
                    ,limit: 20
                    ,limits: [20, 50, 100, 150, 200,500]
                    ,toolbar: '#toolbar'
                    ,cols: [[ //表头
                        {checkbox: true,fixed: true}
                        ,{field: 'id', title: 'ID', sort: true,width:80}
                        ,{field: 'product_id',width:350, title: '产品', templet: function (d) {
                                return d.product.name;
                            }}
                        ,{field: 'custom', title: '客户'}
                        ,{field: 'quantity', title: '数量'}
                        ,{field: 'price', title: '单价'}
                        ,{field: 'amount', title: '总价'}
                        ,{field: 'status', title: '状态'}
                        ,{field: 'date', title: '日期'}
                        ,{fixed: 'right', width: 220, align:'center', toolbar: '#options'}
                    ]]
                    , done: function (res, curr, count) {
                        $('#totalAmount').html(res.totalAmount);
                    }
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.checkout.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/checkout/'+data.id+'/edit';
                    }
                });

                //监听是否显示
                form.on('switch(isShow)', function(obj){
                    var index = layer.load();
                    var url = $(obj.elem).attr('url')
                    var data = {
                        "is_show" : obj.elem.checked==true?1:0,
                        "_method" : "put"
                    }
                    $.post(url,data,function (res) {
                        layer.close(index)
                        layer.msg(res.msg)
                    },'json');
                });

                //按钮批量删除
                $("#listDelete").click(function () {
                    var ids = []
                    var hasCheck = table.checkStatus('dataTable')
                    var hasCheckData = hasCheck.data
                    if (hasCheckData.length>0){
                        $.each(hasCheckData,function (index,element) {
                            ids.push(element.id)
                        })
                    }
                    if (ids.length>0){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.checkout.destroy') }}",{_method:'delete',ids:ids},function (result) {
                                if (result.code==0){
                                    dataTable.reload()
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        })
                    }else {
                        layer.msg('请选择删除项')
                    }
                })

                //搜索
                $("#searchBtn").click(function () {
                    var product_id = $("#product_id").val()
                    var status = $("#status").val();
                    var date = $("#date").val();
                    dataTable.reload({
                        where:{product_id:product_id,status:status,date:date},
                        page:{curr:1}
                    })
                })
            })
        </script>
    @endcan
@endsection