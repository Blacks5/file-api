<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/25
 * Time: 16:57
 */

namespace App\Repository\Get;


use App\Services\OSS;
use Carbon\Carbon;

class AliyunGet implements BaseGet
{
    private $bucket;
    private $expireTime;

    public function __construct()
    {
        $this->bucket = env('OSS_TEST_BUCKET');
        $this->expireTime = Carbon::now()->addDay(1);
    }

    public function getImg($path)
    {
        return OSS::getPrivateObjectURLWithExpireTime($this->bucket, $path, $this->expireTime);
    }
}