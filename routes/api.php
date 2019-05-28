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
Route::get('/store', ['uses' => 'StoreController@index', 'as' => 'api.store']);
Route::get('/store/{number}', ['uses' => 'StoreController@show', 'as' => 'api.show_store']);
Route::get('/error', ['uses' => 'ErrorLogController@index', 'as' => 'api.error']);
Route::get('/error/{number}', ['uses' => 'ErrorLogController@show', 'as' => 'api.show_error']);
