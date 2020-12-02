@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加批次</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.batch.store')}}" method="post">
                @include('admin.batch._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.batch._js')
@endsection
