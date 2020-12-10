@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加报销</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.baoxiao.store')}}" method="post">
                @include('admin.baoxiao._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.baoxiao._js')
@endsection
