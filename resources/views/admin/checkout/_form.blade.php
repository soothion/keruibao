{{csrf_field()}}

<div class="layui-form-item">
    <label for="" class="layui-form-label">产品</label>
    <div class="layui-input-inline">
        <select name="product_id" lay-verify="required" lay-filter="product_id">
            <option value="">请选择产品</option>
            @foreach($products as $product)
                <option value="{{$product->id}}" sale_price="{{$product->sale_price}}" @if(isset($checkout->product_id)&&$checkout->product_id==$product->id)selected @endif >{{$product->id}} - {{$product->name}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">数量</label>
    <div class="layui-input-block">
        <input type="number" name="quantity" lay-filter="quantity"  value="{{$checkout->quantity??old('quantity')}}" lay-verify="required" placeholder="请输入数量" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">单价</label>
    <div class="layui-input-block">
        <input type="text"  name="price" value="{{$checkout->price??old('price')}}" lay-verify="required" placeholder="请输入单价" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">总价</label>
    <div class="layui-input-block">
        <input type="text"  name="amount" value="{{$checkout->amount??old('amount')}}" lay-verify="required" placeholder="请输入总价" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">客户</label>
    <div class="layui-input-block">
        <input type="text" name="custom" value="{{$checkout->custom??old('custom')}}" placeholder="请输入客户" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">日期</label>
    <div class="layui-input-block">
        <input type="text" name="date" id="date" autocomplete="off" value="{{$checkout->date??old('date')}}" placeholder="请输入日期" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <label for="" class="layui-form-label">描述</label>
    <div class="layui-input-block">
        <textarea name="description" placeholder="请输入描述" class="layui-textarea">{{$checkout->description??old('description')}}</textarea>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">状态</label>
    <div class="layui-input-inline">
        <select name="status" >
            <option value="">请选择状态</option>
            <option value="未出库" @if(isset($checkout->status)&&$checkout->status=='未出库')selected @endif >未出库</option>
            <option value="已出库" @if(isset($checkout->status)&&$checkout->status=='已出库')selected @endif >已出库</option>
        </select>
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.checkout')}}" >返 回</a>
    </div>
</div>
