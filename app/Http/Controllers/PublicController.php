<?php
namespace App\Http\Controllers;

use App\Traits\Msg;
use Illuminate\Http\Request;
use Image;

class PublicController extends Controller
{
    use Msg;
    //图片上传处理
    public function uploadImg(Request $request)
    {

        //上传文件最大大小,单位M
        $maxSize = 10;
        //支持的上传图片类型
        $allowed_extensions = ["png", "jpg", "gif", "jpeg"];
        $file = $request->file('file');

        if($file){
            //检查文件是否上传完成
            if ($file->isValid()){
                //检测图片类型
                $ext = $file->getClientOriginalExtension();
                if (!in_array(strtolower($ext),$allowed_extensions)){
                    return redirect(route('admin.back'))->withErrors([
                        'status' => "请上传".implode(",",$allowed_extensions)."格式的图片"
                    ]);
                }
                //检测图片大小
                $size = $file->getClientSize();
                if ($size > $maxSize*1024*1024){
                    return redirect(route('admin.back'))->withErrors([
                        'status' => "图片大小限制".$maxSize."M"
                    ]);
                }
            }else{
                return redirect(route('admin.back'))->withErrors(['status' => $file->getErrorMessage()]);
            }
            $path = '/upload/'.date('Y-m-d').'/';

            if(!is_dir(public_path().$path)){
                if(!mkdir(public_path().$path)){
                    return redirect(route('admin.back'))->withErrors(['status' => '创建目录失败']);
                }
            }

            $newFile = $path.uniqid().".".$file->getClientOriginalExtension();
            $res = Image::make($file)->save(public_path().$newFile);//压缩并保存照片
        }
        if($res){
            $data = [
                'code'  => 0,
                'msg'   => '上传成功',
                'data'  => $newFile,
                'url'   => $newFile
            ];
        }else{
            $data['data'] = $file->getErrorMessage();
        }
        return response()->json($data);
    }



}