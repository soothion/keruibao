@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加流水</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.water.store')}}" method="post">
                @include('admin.water._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.water._js')
@endsection
