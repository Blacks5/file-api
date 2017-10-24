<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/23
 * Time: 18:11
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = ['uuid', 'path', 'original_name', 'provider'];
    protected $hidden = [];
}