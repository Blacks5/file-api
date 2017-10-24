<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/24
 * Time: 11:14
 */

namespace App\Repository;


use App\Models\File;

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
        $this->aliyunDestroy($file->path . $file->uuid);
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