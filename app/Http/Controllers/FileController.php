<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/23
 * Time: 14:09
 */

namespace App\Http\Controllers;


use App\Http\Response;
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

    public function show($fileName)
    {
        $file = OSS::getPublicObjectURL($this->bucket,$fileName);
        dd($file);
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
                'data' => $msg['data'],
            ]);
        }
    }

    public function destory()
    {

    }

}