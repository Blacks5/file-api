<?php
/**
 * Created by PhpStorm.
 * User: OneStep
 * Date: 2017/10/23
 * Time: 17:39
 */

namespace App\Services;


use App\File;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Ramsey\Uuid\Uuid;

class FileUpload
{
    private $uuid;
    private $path;
    private $bucket;
    private $compress = true;
    private $width = 1600;
    private $height = null;
    private $wechatUrl = "https://api.weixin.qq.com/cgi-bin/material/get_material";

    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->getHex();
        $this->path = date('Y/m').'/';
        $this->bucket = env('OSS_TEST_BUCKET');
    }

    /**
     * 阿里云oss上传
     * @param Request $request
     * @return array
     * @author OneStep
     */
    public function AliyunUpload(Request $request)
    {
        $file = $request->file('file');
        $options = ['ContentType'=>$file->getClientMimeType()];
        $upload = OSS::publicUpload(
            $this->bucket,
            $this->path . $this->uuid,
            $this->makeImages($request),
            $options
        );
        if($upload){
            $data = $this->saveToDb($file->getRealPath(),'aliyun');
            if($data){
                return ['status'=>1, 'message'=>'上传文件成功','data'=>$data];
            }
        }
        return ['status'=>0, 'message'=>'上传文件失败', 'data'=>''];
    }

    public function QiniuUpload()
    {

    }

    public function wechatUpload($media_id)
    {
        $this->getWechatFile($media_id);
    }

    private function getWechatFile($media_id)
    {
        $url = $this->wechatUrl .'?access_token=' . env('WECHAT_ACCESS_TOKEN');
        $postData = http_build_query($media_id);
        $options = [
          'http'=> [
            'method'=> 'POST',
              'header' => 'Content-type:application/x-www-form-urlencoded',
              'content' => $postData,
              'timeout' => 15 * 60
          ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($this->wechatUrl, false, $context);
        dd($result);
    }

    /**
     * 返回上传文件的路径(根据compress判断是否压缩)
     * @param Request $request
     * @return string
     */
    private function makeImages(Request $request)
    {
        $file = $request->file('file');
        $filePath = $file->getRealPath();
        if($this->compress){
            $manage = new ImageManager(['driver'=>'gd']);
            $img = $manage->make($filePath);
            $img->resize($this->width, $this->height, function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->orientate();
            $img->save($filePath,75);
        }

        return $filePath;
    }

    /**
     * 保存已经上传的图片信息
     * @param $realName 文件名称
     * @param $provider 上传的服务商
     * @return bool
     * @author OneStep
     */
    private function saveToDb($realName,$provider)
    {
        $file = new File();
        $file->uuid = $this->uuid;
        $file->path = $this->path;
        $file->original_name = $realName;
        $file->provider = $provider;
        //$file->extended_data = $info; 扩展数据 json格式  暂时不用
        if($file->save()){
            return $this->getReturnMessage($realName,$provider);
        }
        return false;
    }

    /**
     * 返回上传图片的相关信息
     * @param $realName
     * @param $provider
     * @return array
     * @author OneStep
     */
    private function getReturnMessage($realName,$provider)
    {
        return $data = [
            'realName' => $realName,
            'uuid' => $this->uuid,
            'path' => $this->path,
            'provider' => $provider
        ];
    }
}