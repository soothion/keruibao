{{csrf_field()}}

<div class="layui-form-item">
    <label for="" class="layui-form-label">产品名</label>
    <div class="layui-input-block">
        <input type="text" name="name" value="{{$product->name??old('name')}}" lay-verify="required" placeholder="请输入产品名" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">成本价</label>
    <div class="layui-input-block">
        <input type="number"  name="cost_price" value="{{$product->cost_price??old('cost_price')}}" lay-verify="number required" placeholder="请输入成本价" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">销售价</label>
    <div class="layui-input-block">
        <input type="number" name="sale_price" value="{{$product->sale_price??old('sale_price')}}" lay-verify="number required" placeholder="请输入销售" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">描述</label>
    <div class="layui-input-block">
        <textarea name="description" placeholder="请输入描述" class="layui-textarea">{{$product->description??old('description')}}</textarea>
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.product')}}" >返 回</a>
    </div>
</div>