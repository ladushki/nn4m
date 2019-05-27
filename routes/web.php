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

Route::get('/', ['uses' => 'ImportController@index', 'as' => 'import.index']);
Route::post('upload', ['uses' => 'ImportController@upload', 'as' => 'import.upload']);
Route::get('results/{id}', ['uses' => 'ImportController@result', 'as' => 'import.result']);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
