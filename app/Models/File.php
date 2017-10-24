<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/23
 * Time: 18:11
 */

namespace App\Models;

class File extends BaseModel
{
    protected $table = 'files';
    protected $fillable = ['uuid', 'path', 'original_name', 'provider'];
    protected $hidden = [];
}