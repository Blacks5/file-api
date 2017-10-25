<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/25
 * Time: 16:55
 */

namespace App\Repository\Get;


interface BaseGet
{
    /**
     * @param $path
     * @return mixed
     * @author OneStep
     */
    public function getImg($path);
}