@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新报销</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.baoxiao.update',['id'=>$baoxiao->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.baoxiao._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.baoxiao._js')
@endsection
