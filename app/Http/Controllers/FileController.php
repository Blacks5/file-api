<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/23
 * Time: 14:09
 */

namespace App\Http\Controllers;


use App\Http\Response;
use App\Repository\FileDestroy;
use App\Repository\FileGet;
use App\Repository\FileUpload;
use Illuminate\Http\Request;

class FileController extends Controller
{

    public function index()
    {

    }

    /**
     * 根据uuid获取图片链接
     * @param $uuid
     * @return static
     * @author OneStep
     */
    public function show($uuid)
    {
        $get = new FileGet();
        $file = $get->getFile($uuid);
        return Response::success([
            'path' => $file
        ]);
    }

    /**
     * 批量获取图片链接地址
     * http://loaclhost/v1/file?images[]=xxxx&images[]=xxxxx
     * @param Request $request
     * @return static
     * @author OneStep
     */
    public function more(Request $request)
    {
        $get = new FileGet();
        $files = $get->getMoreFile($request->input('images'));
        return Response::success([
            'path' => $files
        ]);
        return false;
    }

    /**
     * 上传文件
     * fileName 2017/10/...
     * @param Request $request
     * @return static
     * @author OneStep
     */
    public function store(Request $request)
    {
        $upload = new FileUpload();
        $msg = $upload->AliyunUpload($request);

        if($msg['status']==1){
            return Response::success([
                'data' => $msg['data']
            ]);
        }
    }

    /**
     * 获取微信图片上传到OSS
     * @param $media_id
     * @return static
     * @author OneStep
     */
    public function wechat($media_id)
    {
        $upload = new FileUpload();
        $file = $upload->wechatUpload($media_id);
        if($file){
            return Response::success([
                'data' => $file['data']
            ]);
        }
    }

    public function lists()
    {
        /*$app = new Application(config('wechat'));
        $m = $app->material;
        dd($m->lists('image',1,20));*/
        $file = file_get_contents("https://mmbiz.qpic.cn/mmbiz_jpg/9UCDk3ibhRgHN24RM2uzibAwkV7C0qxoygiaUsqsKK1cynmOWYaVlboyPnkHK1LSTMjGEPZDexaNZTN5HR7B16ibmQ/640");
        $msg = new FileUpload();
        $fileName = 'images/'.date('Ymd').rand(1000,9999).'.jpg';
        file_put_contents($fileName,$file);
        $msg->wechatUpload($fileName);
        //

    }

    /**
     * 删除图片及数据库信息
     * @param $uuid
     * @return static
     * @author OneStep
     */
    public function destroy($uuid)
    {
        $destroy = new FileDestroy();
        if($destroy->destroyFile($uuid)){
            return Response::success([
                'status' => 1,
                'message' => '删除成功!'
            ]);
        }
    }

}