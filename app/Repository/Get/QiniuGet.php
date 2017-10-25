<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/25
 * Time: 17:01
 */

namespace App\Repository\Get;


use Qiniu\Qiniu;

class QiniuGet implements BaseGet
{
    private $client;

    public function __construct()
    {
        $this->client = Qiniu::create([
            'access_key' => env('QINIU_KEY'),
            'secret_key' => env('QINIU_SECRET'),
            'bucket' => env('QINIU_BUCKET'),
            'base_url' => env('QINIU_ENDPOINT')
        ]);
    }

    /**
     * 自己封装的七牛云获取私有链接
     * @param $path
     * @return string
     * @author OneStep
     */
    public function getImg($path)
    {
        $path = env('QINIU_ENDPOINT').'/'.$path.'?&e='.strtotime('+1 day');
        $hash = hash_hmac('sha1',$path,env('QINIU_SECRET'),true);
        $token = env('QINIU_KEY').':'.base64_encode($hash);

        return $path.'&token='.$token;
    }
}