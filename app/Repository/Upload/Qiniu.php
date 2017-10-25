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

    /**
     * 构造函数
     * Qiniu constructor.
     * @author OneStep
     */
    public function __construct()
    {
        $this->client = \Qiniu\Qiniu::create([
            'access_key' => env('QINIU_KEY'),
            'secret_key' => env('QINIU_SECRET'),
            'bucket' => env('QINIU_BUCKET'),
            'base_url' => env('QINIU_ENDPOINT')
        ]);
    }

    /**
     * 七牛云上传图片
     * @param $path
     * @param $uuid
     * @param $filePath
     * @param string $mimeType
     * @return array
     * @author OneStep
     */
    public function upload($path, $uuid, $filePath, $mimeType = 'image/jpg')
    {
        $upload = $this->client->uploadFile($filePath,$path.$uuid);
        if($upload->ok()){
            return [
                'uuid' => $uuid,
                'path' => $path.$uuid
            ];
        }
    }

    /**
     * 删除七牛云图片(偷个懒写这里了 ,以后改)
     * @param $path
     * @return bool|\Qiniu\Client|\Qiniu\Result
     * @author OneStep
     */
    public function destroy($path)
    {
        return $this->client->delete($path);
    }
}