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

class FileGet
{
    public function getFile($uuid)
    {
        $file = File::where('uuid', $uuid)->firstOrFail();

        return $this->aliyunGetPrivateFile($file->path.$file->uuid);
    }

    private function aliyunGetPublicFile($path)
    {
        return OSS::getPublicObjectURL(env('OSS_TEST_BUCKET'), $path);

    }

    private function aliyunGetPrivateFile($path)
    {
        return OSS::getPrivateObjectURLWithExpireTime(env('OSS_TEST_BUCKET'), $path, Carbon::now()->addDay(1));
    }
}