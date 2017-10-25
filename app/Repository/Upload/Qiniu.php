<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/25
 * Time: 16:20
 */

namespace App\Repository\Upload;


class Qiniu implements BaseUpload
{
    private $client;

    public function __construct()
    {
        $this->client = \Qiniu\Qiniu::create([
            'access_key' => env('QINIU_KEY'),
            'secret_key' => env('QINIU_SECRET'),
            'bucket' => env('QINIU_BUCKET')
        ]);
    }

    public function upload($path, $uuid, $filePath, $mimeType = 'image/jpg')
    {
        $upload = $this->client->uploadFile($filePath,$path.$uuid);
        if($upload->ok()){
            return [
                'uuid' => $uuid,
                'path' => $path
            ];
        }
    }
}