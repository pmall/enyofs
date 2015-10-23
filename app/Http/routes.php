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

$app->get('/', 'FilesController@show');
$app->get('{filename:.+}', 'FilesController@show');
$app->post('{filename:.+}', 'FilesController@store');
$app->delete('{filename:.+}', 'FilesController@destroy');
