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

Route::post('login','Api\AuthController@login');

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::post('token/refresh','Api\AuthController@refresh');
    Route::post('logout','Api\AuthController@logout');
    Route::post('user/data','Api\AuthController@user');

    Route::post('imageprofile','Api\ImageProfileController@ImageProfile');
    Route::delete('imageprofile/delete','Api\ImageProfileController@DeleteImageProfile');
    Route::apiResource('user','Api\UsersController');
});

Route::group(['middleware' => ['admin']], function () {
    Route::apiResource('admin/announces','Api\Admin_AnnouncesController');
});