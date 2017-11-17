<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/24
 * Time: 9:26
 */

namespace App\Repository;


use App\Exceptions\BusinessException;
use App\Models\File;
use App\Repository\Get\AliyunGet;
use App\Repository\Get\QiniuGet;
use Carbon\Carbon;

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
        $file = File::where('uuid', $uuid)->first();
        if(empty($file)){
            throw new BusinessException('PARAMETER_ERROR','请提交正确的UUID');
        }

        switch ($file->provider){
            case 'aliyun':
                $providers = new AliyunGet();
                return $providers->getImg($file->path);
                break;
            case 'qiniu':
                $providers = new QiniuGet();
                return $providers->getImg($file->path);
                break;
        }
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
}