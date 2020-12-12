<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Baoxiao;
use App\Models\Water;
use App\Models\Batch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaoxiaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.baoxiao.index');
    }

    public function data(Request $request)
    {
        $param = $request->all();
        $model = Baoxiao::query();
        if (!empty($param['date'])) {
            list($param['start'], $param['end']) = explode(' - ', $param['date']);
            $model = $model->where('created_at', '>=', $param['start']);
            $model = $model->where('created_at', '<=', $param['end']);
        }

        if (!empty($param['status'])) {
            $model = $model->where('status', $param['status']);
        }

        if (!empty($param['amount'])) {
            $model = $model->where('amount', number_format($param['amount'], 2, '.', ''));
        }

        if (!empty($param['water_id'])) {
            $model = $model->where('water_id', $param['water_id']);
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
        return view('admin.baoxiao.create', compact('products', 'batches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['type','batch_id','amount','date', 'status', 'description', 'image']);
        $baoxiao = Baoxiao::create($data);
        return redirect(route('admin.baoxiao'))->with(['status'=>'添加成功']);
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
        $baoxiao = Baoxiao::findOrFail($id);
        $products = Product::all();
        $batches = Batch::all();
        if (!$baoxiao){
            return redirect(route('admin.baoxiao'))->withErrors(['status'=>'产品不存在']);
        }
        return view('admin.baoxiao.edit', compact('baoxiao', 'products', 'batches'));

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
        $baoxiao = Baoxiao::findOrFail($id);
        $data = $request->only(['type','batch_id','amount','date', 'status', 'description', 'image']);
        if ($baoxiao->update($data)){
            return redirect(route('admin.baoxiao'))->with(['status'=>'更新成功']);
        }
        return redirect(route('admin.baoxiao'))->withErrors(['status'=>'系统错误']);
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
        foreach (Baoxiao::whereIn('id',$ids)->get() as $model){
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
        $baoxiaos = Baoxiao::whereIn('id',$ids)->where(['status'=>'已报销'])->get();
        if(count($baoxiaos)){
            return response()->json(['code'=>1,'msg'=>'已报销项目不能重复报销']);
        }
        $totalAmount = Baoxiao::whereIn('id',$ids)->sum('amount');
        $water = Water::create([
            'inout'=>'支出',
            'type'=>'其他',
            'amount'=>$totalAmount,
            'date'=>date('Y-m-d'),
            'description'=>'报销单据:'.implode(',', $ids)
        ]);
        $waterId = $water->id;
        Baoxiao::whereIn('id',$ids)->update([
            'status'=>'已报销',
            'water_id'=>$waterId
        ]); 
        return response()->json(['code'=>0,'msg'=>'报销成功','waterId'=>$waterId]);
    }

}
