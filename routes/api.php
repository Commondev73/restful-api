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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login','Api\AuthController@login');
Route::post('register','Api\RegisterController@Register');
Route::get('announces/search/{data}','Api\PublicAnnouncesController@announces');
Route::get('announces/{id}','Api\PublicAnnouncesController@announcesByID');

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::post('token/refresh','Api\AuthController@refresh');
    Route::post('logout','Api\AuthController@logout');

    Route::post('user/data','Api\AuthController@user');
    Route::post('user/imageprofile','Api\ImageProfileController@ImageProfile');
    Route::delete('user/imageprofile','Api\ImageProfileController@DeleteImageProfile');
    Route::apiResource('user/announces', 'Api\AnnouncesController');
    Route::post('user/update/profile/{id}','Api\UpdateProfileController@Update');
});

Route::group(['middleware' => ['admin']], function () {
    Route::apiResource('admin/announces','Api\Admin_AnnouncesController');
    Route::apiResource('admin/users','Api\UsersController');
});