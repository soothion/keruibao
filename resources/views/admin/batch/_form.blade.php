{{csrf_field()}}

<div class="layui-form-item">
    <label for="" class="layui-form-label">批次号</label>
    <div class="layui-input-block">
        <input type="text" name="batch_number" value="{{$batch->batch_number??old('batch_number')}}" lay-verify="required" placeholder="请输入批次号" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">产品</label>
    <div class="layui-input-inline">
        <select name="product_id">
            <option value="">请选择产品</option>
            @foreach($products as $product)
                <option value="{{$product->id}}" @if(isset($batch->product_id)&&$batch->product_id==$product->id)selected @endif >{{$product->id}} - {{$product->name}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">数量</label>
    <div class="layui-input-block">
        <input type="number" name="quantity" value="{{$batch->quantity??old('quantity')}}" lay-verify="number required" placeholder="请输入数量" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">状态</label>
    <div class="layui-input-inline">
        <select name="status" >
            <option value="">请选择状态</option>
            <option value="生产中" @if(isset($batch->status)&&$batch->status=='生产中')selected @endif >生产中</option>
            <option value="已完成" @if(isset($batch->status)&&$batch->status=='已完成')selected @endif >已完成</option>
        </select>
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.batch')}}" >返 回</a>
    </div>
</div>