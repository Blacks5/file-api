<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/24
 * Time: 9:26
 */

namespace App\Services;


use App\File;
use Carbon\Carbon;
use Faker\Provider\cs_CZ\DateTime;
use GuzzleHttp\Psr7\Request;

class FileGet
{
    /**
     * 获取图片地址
     * @param $uuid
     * @return mixed
     * @author OneStep
     */
    public function getFile($uuid)
    {
        $file = File::where('uuid', $uuid)->firstOrFail();

        return $this->aliyunGetPrivateFile($file->path.$file->uuid);
    }

    /**
     * 批量获取图片
     * @param $uuid
     * @return array
     * @author OneStep
     */
    public function getMoreFile($uuid)
    {
        $data = [];
        if(is_array($uuid)){
            foreach ($uuid as $k => $v){
                $data[$k]['uuid'] = $v;
                $data[$k]['path'] = $this->getFile($v);
            }
        }
        return $data;
    }

    /**
     * 获取阿里云公开链接
     * @param $path
     * @return string
     * @author OneStep
     */
    private function aliyunGetPublicFile($path)
    {
        return OSS::getPublicObjectURL(env('OSS_TEST_BUCKET'), $path);

    }

    /**
     * 获取阿里云私有链接(过期时间1天)
     * @param $path
     * @return mixed
     * @author OneStep
     */
    private function aliyunGetPrivateFile($path)
    {
        return OSS::getPrivateObjectURLWithExpireTime(env('OSS_TEST_BUCKET'), $path, Carbon::now()->addDay(1));
    }
}