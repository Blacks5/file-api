<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/24
 * Time: 16:58
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{

    const MASK = [];
    protected $dateFormat = 'Y-m-d H:i:s';

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    }
}
