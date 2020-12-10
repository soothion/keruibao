{{csrf_field()}}


<div class="layui-form-item">
    <label for="" class="layui-form-label">收入/支出</label>
    <div class="layui-input-inline">
        <select name="inout" lay-verify="required">
            <option value="">请选择收入/支出</option>
            <option value="收入" @if(isset($water->inout)&&$water->inout=='收入')selected @endif >收入</option>
            <option value="支出" @if(isset($water->inout)&&$water->inout=='支出')selected @endif >支出</option>
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">支付方式</label>
    <div class="layui-input-inline">
        <select name="paytype" lay-verify="required">
            <option value="">请选择支付方式</option>
            <option value="银行卡" @if(isset($water->paytype)&&$water->paytype=='银行卡')selected @endif >银行卡</option>
            <option value="支付宝" @if(isset($water->paytype)&&$water->paytype=='支付宝')selected @endif >支付宝</option>
            <option value="未知" @if(isset($water->paytype)&&$water->paytype=='未知')selected @endif >未知</option>
        </select>
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">类别</label>
    <div class="layui-input-inline">
        <select name="type" lay-verify="required">
            <option value="">请选择类别</option>
            <option value="材料" @if(isset($water->type)&&$water->type=='材料')selected @endif >材料</option>
            <option value="订货" @if(isset($water->type)&&$water->type=='订货')selected @endif >订货</option>
            <option value="快递" @if(isset($water->type)&&$water->type=='快递')selected @endif >快递</option>
            <option value="办公" @if(isset($water->type)&&$water->type=='办公')selected @endif >办公</option>
            <option value="工资" @if(isset($water->type)&&$water->type=='工资')selected @endif >工资</option>
            <option value="货款" @if(isset($water->type)&&$water->type=='货款')selected @endif >材料</option>
            <option value="提现" @if(isset($water->type)&&$water->type=='提现')selected @endif >材料</option>
            <option value="其他" @if(isset($water->type)&&$water->type=='其他')selected @endif >其他</option>
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">生产批次</label>
    <div class="layui-input-inline">
        <select name="batch_id">
            <option value="">请选择生产批次</option>
            @foreach($batches as $batch)
                <option value="{{$batch->id}}" @if(isset($water->batch_id)&&$water->batch_id==$batch->id)selected @endif >{{$batch->product->name}} - {{$batch->batch_number}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">金额</label>
    <div class="layui-input-inline">
        <input type="text" name="amount" value="{{$water->amount??old('amount')}}" lay-verify="required" placeholder="请输入金额" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">凭证</label>
    <div class="layui-input-block">
        <div class="layui-upload">
            <button type="button" class="layui-btn" id="uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
            <div class="layui-upload-list" >
                <ul id="layui-upload-box" class="layui-clear">
                    @if(isset($water->image))
                        <li><img onclick="openImg();" id="showImg" src="{{ $water->image }}" /><p>上传成功</p></li>
                    @endif
                </ul>
                <input type="hidden" name="image" id="image" value="{{ $water->image??'' }}">
            </div>
        </div>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">日期</label>
    <div class="layui-input-inline">
        <input type="text" name="date" lay-verify="required" id="date" autocomplete="off" value="{{$water->date??old('date')}}" placeholder="请输入日期" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">描述</label>
    <div class="layui-input-block">
        <textarea name="description" placeholder="请输入描述" class="layui-textarea">{{$water->description??old('description')}}</textarea>
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.water')}}" >返 回</a>
    </div>
</div>

