@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加出库</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.checkout.store')}}" method="post">
                @include('admin.checkout._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.checkout._js')
@endsection
