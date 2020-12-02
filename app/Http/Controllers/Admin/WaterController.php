<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Water;
use App\Models\Batch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WaterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.water.index');
    }

    public function data(Request $request)
    {
        $param = $request->all();
        $model = Water::query();
        if (!empty($param['date'])) {
            list($param['start'], $param['end']) = explode(' - ', $param['date']);
            $model = $model->where('created_at', '>', $param['start']);
            $model = $model->where('created_at', '<=', $param['end']);
        }

        if (!empty($param['status'])) {
            $model = $model->where('status', $param['status']);
        }

        if (!empty($param['check_id'])) {
            $model = $model->where('check_id', $param['check_id']);
        }

        if (!empty($param['inout'])) {
            $model = $model->where('inout', $param['inout']);
        }

        if (!empty($param['type'])) {
            $model = $model->where('type', $param['type']);
        }
        $totalAmountModel = $model;
        $totalAmount = $totalAmountModel->sum('amount');
        $res = $model->orderBy('date','desc')->with('product')->paginate($request->get('limit',30))->toArray();
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data'],
            'totalAmount'=>$totalAmount
        ];
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        $batches = Batch::all();
        return view('admin.water.create', compact('products', 'batches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['inout','type','batch_id','amount','date', 'status', 'description', 'image']);
        $water = Water::create($data);
        return redirect(route('admin.water'))->with(['status'=>'添加成功']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $water = Water::findOrFail($id);
        $products = Product::all();
        $batches = Batch::all();
        if (!$water){
            return redirect(route('admin.water'))->withErrors(['status'=>'产品不存在']);
        }
        return view('admin.water.edit', compact('water', 'products', 'batches'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $water = Water::findOrFail($id);
        $data = $request->only(['inout','type','batch_id','amount','date', 'status', 'description', 'image']);
        if ($water->update($data)){
            return redirect(route('admin.water'))->with(['status'=>'更新成功']);
        }
        return redirect(route('admin.water'))->withErrors(['status'=>'系统错误']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        foreach (Water::whereIn('id',$ids)->get() as $model){
            //删除产品
            $model->delete();
        }
        return response()->json(['code'=>0,'msg'=>'删除成功']);
    }

    public function check(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择操作项']);
        }
        $check_id = date('Ymd').rand(1000,9999);
        $waters = Water::where(['check_id'=>$check_id])->get();
        if(count($waters)){
            return response()->json(['code'=>1,'msg'=>'系统错误,请重试']);
        }
        $waters = Water::whereIn('id',$ids)->where(['status'=>'已入帐'])->get();
        if(count($waters)){
            return response()->json(['code'=>1,'msg'=>'已入帐项目不能重复入帐']);
        }
        Water::whereIn('id',$ids)->update([
            'check_id'=>$check_id,
            'status'=>'已入帐'
        ]);
        return response()->json(['code'=>0,'msg'=>'入帐成功']);
    }

}
