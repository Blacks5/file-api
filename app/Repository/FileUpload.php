<?php
/**
 * Created by PhpStorm.
 * User: OneStep
 * Date: 2017/10/23
 * Time: 17:39
 */

namespace App\Repository;


use App\Models\File;
use App\Repository\Upload\Aliyun;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Ramsey\Uuid\Uuid;
use App\Services\OSS;

trait FileUpload
{
    private $compress = true;
    private $width = 1600;
    private $height = null;
    private $storageProviders = 'aliyun';
    private $driver;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->getHex();
        $this->path = date('Y/m').'/';
        switch ($this->storageProviders){
            case 'aliyun':
                $this->driver = new Aliyun();
                break;
            case 'qiniu':
                $this->driver = new Qiniu();
                break;
        }
    }

    /**
     * 通用上传
     * @param Request $request
     * @return array
     * @author OneStep
     */
    public function Upload(Request $request)
    {
        $fileInfo = $this->getPath($request);

        $uuid = $this->getUuid();
        $key = strtotime('Y/m/').$uuid;
        $filePath = $fileInfo['path'];
        $mimeType = $fileInfo['mimeType'];



        $upload = $this->driver->upload($key, $filePath, $mimeType);
        if($upload){
            $data = $this->saveToDb($filePath, $this->storageProviders);
            if($data){
                return ['status'=>1, 'message'=>'上传文件成功','data'=>$data];
            }
        }
        return ['status'=>0, 'message'=>'上传文件失败', 'data'=>''];
    }

    /**
     * 设置上传图片的UUID
     * @return string
     * @author OneStep
     */
    private function getUuid()
    {
        return Uuid::uuid4()->getHex();
    }

    /**
     * 获取微信图片地址,保存到服务器,返回服务器的图片地址
     * @param $media_id
     * @return string
     * @author OneStep
     */
    private function getWeChatFile($media_id)
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
     * @return array
     * @author OneStep
     */
    private function getPath(Request $request)
    {
        if(empty($request->file('file'))){
            $filePath = $this->getWeChatFile($request->input('file'));
            $fileMimeType = 'image/jpg';
        }else{
            $file = $request->file('file');
            $fileMimeType = $file->getClientMimeType();
            $filePath = $file->getRealPath();
        }

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

        return $fileInfo = [
            'Path' => $filePath,
            'mimeType' => $fileMimeType
        ];
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