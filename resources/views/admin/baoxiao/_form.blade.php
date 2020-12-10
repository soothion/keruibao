{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">类别</label>
    <div class="layui-input-inline">
        <select name="type" lay-verify="required">
            <option value="">请选择类别</option>
            <option value="材料" @if(isset($baoxiao->type)&&$baoxiao->type=='材料')selected @endif >材料</option>
            <option value="订货" @if(isset($baoxiao->type)&&$baoxiao->type=='订货')selected @endif >订货</option>
            <option value="快递" @if(isset($baoxiao->type)&&$baoxiao->type=='快递')selected @endif >快递</option>
            <option value="办公" @if(isset($baoxiao->type)&&$baoxiao->type=='办公')selected @endif >办公</option>
            <option value="工资" @if(isset($baoxiao->type)&&$baoxiao->type=='工资')selected @endif >工资</option>
            <option value="其他" @if(isset($baoxiao->type)&&$baoxiao->type=='其他')selected @endif >其他</option>
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">生产批次</label>
    <div class="layui-input-inline">
        <select name="batch_id">
            <option value="">请选择生产批次</option>
            @foreach($batches as $batch)
                <option value="{{$batch->id}}" @if(isset($baoxiao->batch_id)&&$baoxiao->batch_id==$batch->id)selected @endif >{{$batch->product->name}} - {{$batch->batch_number}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">金额</label>
    <div class="layui-input-inline">
        <input type="text" name="amount" value="{{$baoxiao->amount??old('amount')}}" lay-verify="required" placeholder="请输入金额" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">凭证</label>
    <div class="layui-input-block">
        <div class="layui-upload">
            <button type="button" class="layui-btn" id="uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
            <div class="layui-upload-list" >
                <ul id="layui-upload-box" class="layui-clear">
                    @if(isset($baoxiao->image))
                        <li><img onclick="openImg();" id="showImg" src="{{ $baoxiao->image }}" /><p>上传成功</p></li>
                    @endif
                </ul>
                <input type="hidden" name="image" id="image" value="{{ $baoxiao->image??'' }}">
            </div>
        </div>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">日期</label>
    <div class="layui-input-inline">
        <input type="text" name="date" lay-verify="required" id="date" autocomplete="off" value="{{$baoxiao->date??old('date')}}" placeholder="请输入日期" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">状态</label>
    <div class="layui-input-inline">
        <select name="status">
            <option value="">请选择状态</option>
            <option value="未报销" @if(isset($baoxiao->status)&&$baoxiao->status=='未报销')selected @endif >未报销</option>
            <option value="已报销" @if(isset($baoxiao->status)&&$baoxiao->status=='已报销')selected @endif >已报销</option>
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">描述</label>
    <div class="layui-input-block">
        <textarea name="description" placeholder="请输入描述" class="layui-textarea">{{$baoxiao->description??old('description')}}</textarea>
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.baoxiao')}}" >返 回</a>
    </div>
</div>

