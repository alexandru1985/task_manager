<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'tasks' => 'API\TasksController',
]);
Route::get('get-clients','API\TasksController@getClients')->name('get-clients');
Route::get('get-projects','API\TasksController@getProjects')->name('get-projects');
Route::get('get-users','API\TasksController@getUsers')->name('get-users');
Route::post('save-csv-to-db', 'API\ImportCSVController@saveCSVtoDb')->name('save-csv-to-db');
