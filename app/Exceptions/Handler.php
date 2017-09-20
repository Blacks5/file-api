<?php

namespace App\Exceptions;

use Exception;
use App\Http\Response;
use App\Exceptions\BusinessException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (is_a($e, HttpException::class)) {
            switch ($e->getStatusCode()) {
                case 401:
                    return Response::error(BusinessException::UNAUTHORIZED, '未授权', 401);
                break;
                case 403:
                    return Response::error(BusinessException::FORBIDDEN, '没有权限', 403);
                break;
                case 404:
                    return Response::error(BusinessException::RESOURCE_NOT_FOUND, '资源没找到', 404);
                break;
            }
        }
        return parent::render($request, $e);
    }
}
