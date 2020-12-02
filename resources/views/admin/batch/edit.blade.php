@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新批次</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.batch.update',['id'=>$batch->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.batch._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.batch._js')
@endsection
