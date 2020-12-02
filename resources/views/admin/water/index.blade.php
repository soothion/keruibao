@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>
                @can('finance.water.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.water.create') }}">添 加</a>
                @endcan
                @can('finance.water.edit')
                    <a class="layui-btn layui-btn-sm" id="listCheck">批量入帐</a>
                @endcan
                <button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>
            </div>

            <div class="layui-form">
                <div class="layui-input-inline">
                    <select name="inout" id="inout">
                        <option value="">请选择收入/支出</option>
                        <option value="收入">收入</option>
                        <option value="支出">支出</option>
                    </select>
                </div>
                 <div class="layui-input-inline">
                    <select name="type" id="type">
                        <option value="">请选择类别</option>
                        <option value="材料">材料</option>
                        <option value="订货">订货</option>
                        <option value="办公">办公</option>
                        <option value="工资">工资</option>
                        <option value="货款">材料</option>
                        <option value="提现">材料</option>
                        <option value="其他">其他</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <select name="status" id="status">
                        <option value="">请选择状态</option>
                        <option value="初始化">初始化</option>
                        <option value="已入帐">已入帐</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                        <input type="text" name="batch_id" id="batch_id" placeholder="请输入入帐流水号" class="layui-input">
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
                    @can('finance.water.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('finance.water.destroy')
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
                    ,url: "{{ route('admin.water.data') }}" //数据接口
                    ,page: true //开启分页
                    , limits: [20, 50, 100, 150, 200,500]
                    , toolbar: '#toolbar'
                    ,cols: [[ //表头
                        {checkbox: true,fixed: true}
                        ,{field: 'id', title: 'ID', sort: true,width:80}
                        ,{field: 'inout', title: '收入/支出'}
                        ,{field: 'type', title: '类别'}
                        ,{field: 'date', title: '日期'}
                        ,{field: 'amount', title: '金额'}
                        ,{field: 'status', title: '状态'}
                        ,{field: 'description', title: '描述'}
                        ,{field: 'created_at', title: '创建时间'}
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
                            $.post("{{ route('admin.water.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/water/'+data.id+'/edit';
                    }
                });

                laydate.render({
                    elem: '#date',
                    type: 'datetime',
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
                            $.post("{{ route('admin.water.destroy') }}",{_method:'delete',ids:ids},function (result) {
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
                        layer.confirm('确认批量入帐吗？', function(index){
                            $.post("{{ route('admin.water.check') }}",{_method:'put',ids:ids},function (result) {
                                if (result.code==0){
                                    dataTable.reload()
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        })
                    }else {
                        layer.msg('请选择入帐项')
                    }
                })

                //搜索
                $("#searchBtn").click(function () {
                    var inout = $("#inout").val()
                    var type = $("#type").val();
                    var check_id = $("#check_id").val();
                    var status = $("#status").val();
                    var date = $("#date").val();
                    dataTable.reload({
                        where:{inout:inout,type:type,check_id:check_id,status:status,date:date},
                        page:{curr:1}
                    })
                })
            })
        </script>
    @endcan
@endsection