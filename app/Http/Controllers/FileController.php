<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/23
 * Time: 14:09
 */

namespace App\Http\Controllers;


use App\Http\Response;
use App\Services\FileGet;
use App\Services\FileUpload;
use App\Services\OSS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class FileController extends Controller
{
    private $bucket = '';
    public function __construct()
    {
        $this->bucket = env('OSS_TEST_BUCKET');
    }

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

    public function destroy($uuid)
    {

    }

}