<?php
/**
 * Created by PhpStorm.
 * User: OneStep
 * Date: 2017/10/23
 * Time: 17:39
 */

namespace App\Services;


use EasyWeChat\Foundation\Application;
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

    /**
     * 将微信图片上传到OSS,并删除服务器文件
     * @param $media_id
     * @return array
     * @author OneStep
     */
    public function wechatUpload($media_id)
    {
        $path = $this->getWechatFile($media_id);
        $upload = OSS::publicUpload(
            $this->bucket,
            $this->path.$this->uuid,
            $path,
            ['ContentType'=>'image/jpeg']
        );

        if($upload){
            $data = $this->saveToDb($path,'aliyun');
            if($data){
                return ['status'=>1,'message'=>'上传成功','data'=>$data];
            }
        }
        return [
            'status' => 0,
            'message' => '上传失败!'
        ];

    }

    /**
     * 获取微信图片地址,保存到服务器,返回服务器的图片地址
     * @param $media_id
     * @return string
     * @author OneStep
     */
    private function getWechatFile($media_id)
    {
        $app = new Application(config('wechat'));
        $material = $app->material;

        $images = $material->get($media_id);
        $fileName = strtotime('Ymd').rand(1000,9999).'.jpg';
        file_put_contents(strtotime('Ymd').rand(1000,9999).'jpg',$images);
        return $fileName;
    }

    /**
     * 返回上传文件的路径(根据compress判断是否压缩)
     * @param Request $request
     * @return string
     * @author OneStep
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