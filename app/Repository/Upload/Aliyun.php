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

    public function upload($key, $filePath, $mimeType = 'image/jpg')
    {
        $upload = OSS::publicUpload(
          $this->bucket,
          $key,
          $filePath,
          ['ContentType' => $mimeType]
        );
        return $upload;
    }
}