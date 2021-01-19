<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Checkout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.checkout.index', compact('products'));
    }

    public function data(Request $request)
    {

        $param = $request->all();
        $model = Checkout::query();
        if (!empty($param['date'])) {
            list($param['start'], $param['end']) = explode(' - ', $param['date']);
            $model = $model->where('date', '>=', $param['start']);
            $model = $model->where('date', '<=', $param['end']);
        }

        if (!empty($param['status'])) {
            $model = $model->where('status', $param['status']);
        }

        if (!empty($param['product_id'])) {
            $model = $model->where('product_id', $param['product_id']);
        }

        $totalAmountModel = $model;
        $totalAmount = $totalAmountModel->sum('amount');
        $res = $model->orderBy('date','desc')->orderBy('id','desc')->with('product')->paginate($request->get('limit',30))->toArray();
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data'],
            'totalAmount'=>$totalAmount
        ];
        return response()->json($data);



        $model = Checkout::query();
        $res = $model->orderBy('date','desc')->orderBy('id','desc')->with('product')->paginate($request->get('limit',30))->toArray();
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
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
        return view('admin.checkout.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['product_id','quantity','status','price', 'amount', 'date', 'custom', 'description']);
        $checkout = Checkout::create($data);
        return redirect(route('admin.checkout'))->with(['status'=>'添加成功']);
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
        $checkout = Checkout::findOrFail($id);
        $products = Product::all();
        if (!$checkout){
            return redirect(route('admin.checkout'))->withErrors(['status'=>'产品不存在']);
        }
        return view('admin.checkout.edit', compact('checkout', 'products'));

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
        $checkout = Checkout::findOrFail($id);
        $data = $request->only('product_id','quantity','price', 'status','amount', 'date', 'custom', 'description');
        if ($checkout->update($data)){
            return redirect(route('admin.checkout'))->with(['status'=>'更新成功']);
        }
        return redirect(route('admin.checkout'))->withErrors(['status'=>'系统错误']);
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
        foreach (Checkout::whereIn('id',$ids)->get() as $model){
            //删除产品
            $model->delete();
        }
        return response()->json(['code'=>0,'msg'=>'删除成功']);
    }

}
