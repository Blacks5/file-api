<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/25
 * Time: 9:02
 */

namespace App\Repository\Upload;


interface BaseUpload
{
    /**
     * 上传方法
     * @return mixed
     * @author OneStep
     */
    public function upload($path, $uuid, $filePath, $mimeType = 'image/jpg');
}