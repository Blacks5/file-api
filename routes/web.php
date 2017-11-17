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


$router->group(['prefix'=> 'v1'], function () use ($router){
    $router->get('files/images', 'FileController@more');
    $router->get('files/{uuid}', 'FileController@show');
    $router->post('files', 'FileController@store');
    $router->delete('files/{uuid}', 'FileController@destroy');
});
