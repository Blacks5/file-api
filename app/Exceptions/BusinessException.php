<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class BusinessException extends HttpException
{
    const UNKNOWN_ERROR      = 'UNKNOWN_ERROR';       //未知错误
    const SERVER_ERROR       = 'SERVER_ERROR';        //服务端错误
    const PARAMETER_ERROR    = 'PARAMETER_ERROR';     // 参数错误
    const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';  // 资源没找到
    const METHOD_NOT_ALLOWED = 'METHOD_NOT_ALLOWED';  // 方法不被允许
    const FORBIDDEN          = 'FORBIDDEN';           // 没有权限
    const UNAUTHORIZED       = 'UNAUTHORIZED';        // 未授权
    const BAD_REQUEST        = 'BAD_REQUEST';         // 错误的请求

    private $business_code;

    private static $status_code = [
        self::UNKNOWN_ERROR      => 500,
        self::SERVER_ERROR       => 500,
        self::PARAMETER_ERROR    => 422,
        self::RESOURCE_NOT_FOUND => 404,
        self::METHOD_NOT_ALLOWED => 405,
        self::FORBIDDEN          => 403,
        self::UNAUTHORIZED       => 401,
        self::BAD_REQUEST        => 400,
    ];

    public function __construct($business_code = self::UNKNOWN_ERROR, $message = "", $status_code = 0)
    {
        $this->business_code = $business_code;
        if (!$status_code) {
            $status_code = array_get(self::$status_code, $business_code, 400);
        }
        parent::__construct($status_code, $message);
    }

    public function getBusinessCode()
    {
        return $this->business_code;
    }

    public static function businessCode($status_code)
    {
        $business_code = array_search($status_code, self::$status_code);
        if ($business_code !== false) {
            return $business_code;
        }
        return self::UNKNOWN_ERROR;
    }
}
