<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/24
 * Time: 11:14
 */

namespace App\Repository;


use App\Models\File;
use App\Repository\Upload\Qiniu;
use App\Services\OSS;

class FileDestroy
{
    /**
     * 删除图片
     * @param $uuid
     * @return int
     * @author OneStep
     */
    public function destroyFile($uuid)
    {
        $file = File::where('uuid', $uuid)->firstOrFail();
        switch ($file->provider){
            case 'aliyun':
                $this->aliyunDestroy($file->path);
                break;
            case 'qiniu':
                $this->qiniuDestroy($file->path);
                break;
        }

        return $this->DbDestroy($file->id);
    }

    /**
     * 删除阿里云OSS里的文件
     * @param $path
     * @return bool
     * @author OneStep
     */
    private function aliyunDestroy($path)
    {
        return OSS::publicDeleteObject(env('OSS_TEST_BUCKET'), $path);
    }

    /**
     * 删除七牛云的图片
     * @param $path
     * @return bool|\Qiniu\Client|\Qiniu\Result
     * @author OneStep
     */
    private function qiniuDestroy($path)
    {
        $destroy = new Qiniu();
        return $destroy->destroy($path);
    }

    /**
     * 删除数据库中的数据
     * @param $id
     * @return int
     * @author OneStep
     */
    private function DbDestroy($id)
    {
        return File::destroy($id);
    }
}