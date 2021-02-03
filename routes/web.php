<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/', 'TasksController@index');
// Route::get('/import-csv', 'ImportCSVController@index')->name('import-csv');
// Route::post('/save-csv-to-db', 'ImportCSVController@saveCSVtoDb')->name('save-csv-to-db');
// Route::resource('tasks', 'TasksController');
// Route::get('{path}', 'HomeController@index')->where('path', '([A-z\d-\/_.]+)?')->name('home');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/', 'HomeController@index')->name('home');
Route::get('{path}', 'HomeController@index')->where('path', '([A-z\d-\/_.]+)?')->name('home');
