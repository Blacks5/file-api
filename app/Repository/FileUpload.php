<?php
/**
 * Created by PhpStorm.
 * User: OneStep
 * Date: 2017/10/23
 * Time: 17:39
 */

namespace App\Repository;


use App\Exceptions\BusinessException;
use App\Models\File;
use App\Repository\Upload\Aliyun;
use App\Repository\Upload\Qiniu;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Ramsey\Uuid\Uuid;

trait FileUpload
{
    private $compress = true;
    private $width = 1600;
    private $height = null;
    private $storageProviders = 'aliyun';
    private $driver;

    public function __construct()
    {
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
    public function upload(Request $request)
    {
        $fileInfo = $this->getPath($request);
        $uuid = $this->getUuid();
        $path = date('Y/m/');
        $filePath = $fileInfo['path'];
        $mimeType = $fileInfo['mimeType'];

        $upload = $this->driver->upload($path, $uuid, $filePath, $mimeType);
        $data = $this->saveToDb($upload, $fileInfo['realName'], $this->storageProviders);

        $this->destroyImg($filePath);
        return $data;
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
        $fileName = 'images/wx/'.strtotime('Ymd').rand(1000,9999).'.jpg';
        file_put_contents($fileName,$images);
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
        //判断上传文件还是微信media_id
        if(empty($request->file())){
            $filePath = $this->getWeChatFile($request->input('image'));

            $fileMimeType = 'image/jpeg';
            $fileName = $filePath;
        }else{
            $file = $request->file('image');
            $fileMimeType = $file->getClientMimeType();
            $filePath = $file->getRealPath();
            $fileName = $file->getFilename();
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
            'path' => $filePath,
            'mimeType' => $fileMimeType,
            'realName' => $fileName
        ];
    }

    /**
     * 保存已经上传的图片信息
     * @param $info 'array([uuid,path])'
     * @param $realName '文件名称'
     * @param $provider '上传的服务商'
     * @return array
     * @author OneStep
     */
    private function saveToDb($info, $realName, $provider)
    {
        try{
            $file = new File();
            $file->uuid = $info['uuid'];
            $file->path = $info['path'];
            $file->original_name = $realName;
            $file->provider = $provider;
            $file->save();
        }catch (\Exception $exception){
            throw new BusinessException('SERVER_ERROR','数据保存失败'.$exception->getMessage());
        }

        return $this->getReturnMessage($info, $realName, $provider);

    }

    /**
     * 返回上传图片的相关信息
     * @param $info
     * @param $realName
     * @param $provider
     * @return array
     * @author OneStep
     */
    private function getReturnMessage($info, $realName,$provider)
    {
        $getFile = new FileGet();
        return $data = [
            'realName' => $realName,
            'uuid' => $info['uuid'],
            'path' => $info['path'],
            'provider' => $provider,
            'url' => $getFile->getFile($info['uuid'])
        ];
    }

    /**
     * 删除保存在服务器的图片(针对微信)
     * @param $realPath
     * @return bool
     * @author OneStep
     */
    private function destroyImg($realPath)
    {
        if(strpos($realPath, 'images/wx/')==0){
            unlink($realPath);
        }
        return true;
    }
}