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
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class FileController extends Controller
{
    use FileUpload;

    public function index()
    {

    }

    /**
     * 根据uuid获取图片链接
     * @param $uuid
     * @return Response
     * @author OneStep
     */
    public function show($uuid)
    {
        $get = new FileGet();
        $file = $get->getFile($uuid);
        return Response::success($file);
    }

    /**
     * 批量获取图片链接地址
     * http://loaclhost/v1/file?images[]=xxxx&images[]=xxxxx
     * @param Request $request
     * @return Response
     * @author OneStep
     */
    public function more(Request $request)
    {
        $get = new FileGet();
        $files = $get->getMoreFile($request->input('images'));
        return Response::success($files);
    }

    /**
     * 上传文件
     * @param Request $request->file('image')
     * @return Response
     * @author OneStep
     */
    public function store(Request $request)
    {
        $msg = $this->upload($request);

        return Response::success($msg);
    }

    /**
     * 获取微信图片上传到OSS
     * @param $media_id
     * @return Response
     * @author OneStep
     */
    public function wechat($media_id)
    {
        $file = $this->upload($media_id);
        return Response::success($file);
    }

    public function lists()
    {
        $app = new Application(config('wechat'));
        $m = $app->material;
        dd($m->lists('image',1,20));


    }

    /**
     * 删除图片及数据库信息
     * @param $uuid
     * @return Response
     * @author OneStep
     */
    public function destroy($uuid)
    {
        $destroy = new FileDestroy();
        $destroy->destroyFile($uuid);
        return Response::success('删除成功!');
    }

}