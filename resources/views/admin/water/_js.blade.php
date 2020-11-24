<style>
    #layui-upload-box li{
        width: 120px;
        height: 100px;
        float: left;
        position: relative;
        overflow: hidden;
        margin-right: 10px;
        border:1px solid #ddd;
    }
    #layui-upload-box li img{
        width: 100%;
    }
    #layui-upload-box li p{
        width: 100%;
        height: 22px;
        font-size: 12px;
        position: absolute;
        left: 0;
        bottom: 0;
        line-height: 22px;
        text-align: center;
        color: #fff;
        background-color: #333;
        opacity: 0.6;
    }
    #layui-upload-box li i{
        display: block;
        width: 20px;
        height:20px;
        position: absolute;
        text-align: center;
        top: 2px;
        right:2px;
        z-index:999;
        cursor: pointer;
    }
</style>
<script>
    layui.use(['upload', 'form', 'laydate'],function () {
        var upload = layui.upload
        var form = layui.form
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
          elem: '#date' //指定元素
        });

        //普通图片上传
        var uploadInst = upload.render({
            elem: '#uploadPic'
            ,url: '{{ route("uploadImg") }}'
            ,multiple: false
            ,data:{"_token":"{{ csrf_token() }}"}
            ,before: function(obj){
                obj.preview(function(index, file, result){
                    $('#layui-upload-box').html('<li><img onclick="openImg();" id="showImg" src="'+result+'" /><p>上传中</p></li>')
                });

            }
            ,done: function(res){
                //如果上传失败
                if(res.code == 0){
                    $("#image").val(res.url);
                    $("#showImg").src(res.url);
                    $('#layui-upload-box li p').text('上传成功');
                    return layer.msg(res.msg);
                }
                return layer.msg(res.msg);
            }
        });

        
    })


    function openImg() {
        var idBar = '#showImg';
        var src = $(idBar)[0].src;
        var width = $(idBar).width();
        var height = $(idBar).height();
        var scaleWH = width / height;
        var bigH = 550;
        var bigW = scaleWH * bigH;
        if (bigW > 1000) {
            bigW = 1000;
            bigH = bigW / scaleWH;
        } // 放大预览图片
        parent.layer.open({
            type: 1,
            title: false,
            closeBtn: 1,
            shadeClose: true,
            area: [bigW + 'px', bigH + 'px'], //宽高
            content: '<img width="' + bigW + '" src="' + src + '" />'
        });
    }
</script>
