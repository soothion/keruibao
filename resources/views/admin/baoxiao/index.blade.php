@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>
                @can('finance.baoxiao.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.baoxiao.create') }}">添 加</a>
                @endcan
                @can('finance.baoxiao.edit')
                    <a class="layui-btn layui-btn-sm" id="listCheck">批量报销</a>
                @endcan
                <button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>
            </div>

            <div class="layui-form">
                 <div class="layui-input-inline">
                    <select name="type" id="type">
                        <option value="">请选择类别</option>
                        <option value="材料">材料</option>
                        <option value="订货">订货</option>
                        <option value="快递">快递</option>
                        <option value="办公">办公</option>
                        <option value="工资">工资</option>
                        <option value="其他">其他</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <select name="status" id="status">
                        <option value="">请选择状态</option>
                        <option value="未报销">未报销</option>
                        <option value="已报销">已报销</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                        <input type="text" name="amount" id="amount" placeholder="请输入金额" class="layui-input">
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
                    @can('finance.baoxiao.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('finance.baoxiao.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('finance.manage')
        <script type="text/html" id="toolbar">
            <h5>总金额:<span id="totalAmount">0</span>元</h5>
        </script>
        <script>
            layui.use(['layer','table','form', 'laydate'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                var laydate = layui.laydate;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 700
                    ,url: "{{ route('admin.baoxiao.data') }}" //数据接口
                    ,page: true //开启分页
                    ,limit: 20
                    , limits: [20, 50, 100, 150, 200,500]
                    , toolbar: '#toolbar'
                    ,cols: [[ //表头
                        {checkbox: true,fixed: true}
                        ,{field: 'id', title: 'ID', sort: true,width:80}
                        ,{field: 'type', title: '类别'}
                        ,{field: 'date', title: '日期'}
                        ,{field: 'amount', title: '金额'}
                        ,{field: 'status', title: '状态'}
                        ,{field: 'description', title: '描述'}
                        ,{field: 'water_id', title: '报销流水号'}
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
                            $.post("{{ route('admin.baoxiao.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/baoxiao/'+data.id+'/edit';
                    }
                });

                laydate.render({
                    elem: '#date',
                    type: 'date',
                    // value: '{{ date('Y-m-d 00:00:00').' - '.date('Y-m-d 23:59:59')}}',
                    range: true
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
                            $.post("{{ route('admin.baoxiao.destroy') }}",{_method:'delete',ids:ids},function (result) {
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

                  //按钮批量删除
                $("#listCheck").click(function () {
                    var ids = []
                    var hasCheck = table.checkStatus('dataTable')
                    var hasCheckData = hasCheck.data
                    if (hasCheckData.length>0){
                        $.each(hasCheckData,function (index,element) {
                            ids.push(element.id)
                        })
                    }
                    if (ids.length>0){
                        layer.confirm('确认批量报销吗？', function(index){
                            $.post("{{ route('admin.baoxiao.check') }}",{_method:'put',ids:ids},function (result) {
                                if (result.code==0){
                                    dataTable.reload()
                                }
                                layer.close(index);
                                layer.msg(result.msg+',对应流水号:'+result.waterId)
                            });
                        })
                    }else {
                        layer.msg('请选择报销项')
                    }
                })

                //搜索
                $("#searchBtn").click(function () {
                    var inout = $("#inout").val()
                    var amount = $("#amount").val()
                    var type = $("#type").val();
                    var paytype = $("#paytype").val();
                    var status = $("#status").val();
                    var date = $("#date").val();
                    dataTable.reload({
                        where:{amount:amount,inout:inout,type:type,paytype:paytype,status:status,date:date},
                        page:{curr:1}
                    })
                })
            })
        </script>
    @endcan
@endsection