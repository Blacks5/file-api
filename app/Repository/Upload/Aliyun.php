<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/25
 * Time: 9:31
 */

namespace App\Repository\Upload;


use App\Services\OSS;

class Aliyun implements BaseUpload
{
    private $bucket = '';

    public function __construct()
    {
        $this->bucket = env('OSS_TEST_BUCKET');
    }

    /**
     * 上传操作
     * @param $key '2017/10/'
     * @param $uuid '28383b8985ab4c319653e2a6fc37fa94'
     * @param $filePath '要上传文件的路径'
     * @param string $mimeType
     * @return array
     * @author OneStep
     */
    public function upload($key, $uuid,$filePath, $mimeType = 'image/jpeg')
    {
        $upload = OSS::publicUpload(
          $this->bucket,
          $key.$uuid,
          $filePath,
          ['ContentType' => $mimeType]
        );
         if($upload){
             $this->destroyImg($filePath);
             return [
                 'uuid' => $uuid,
                 'path' => $key,
             ];
         }
    }
}