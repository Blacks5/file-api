<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/*
$router->group(['prefix'=> 'v1'], function () use ($router){
    $router->get('file', 'FileController@more');
    $router->get('file/{uuid}', 'FileController@show');
    $router->post('file', 'FileController@store');
    $router->post('file/wechat', 'FileController@wechat');
    $router->get('file/wechat', 'FileController@lists');
    $router->delete('file/{uuid}', 'FileController@destroy');
});*/

$router->group(['prefix'=> 'v2'], function () use ($router){
    $router->post('file', 'FilesController@store');
    $router->get('file/{uuid}', 'FilesController@show');
});