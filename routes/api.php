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
Route::get('announces','Api\PublicAnnouncesController@announces');
Route::get('announces/search/{keyword?}/{atype?}/{ptype?}/{bedroom?}/{area?}/{price?}','Api\PublicAnnouncesController@search');
Route::get('announces/{id}','Api\PublicAnnouncesController@announcesByID');

Route::get('province','Api\DistrictsController@province');
Route::get('amphoe/{code?}','Api\DistrictsController@amphoe');
Route::get('district/{code?}','Api\DistrictsController@district');
Route::get('districts/{code?}','Api\DistrictsController@districts');

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::post('token/refresh','Api\AuthController@refresh');
    Route::post('logout','Api\AuthController@logout');

    Route::post('user/data','Api\AuthController@user');
    Route::post('user/imageprofile','Api\ImageProfileController@ImageProfile');
    Route::delete('user/imageprofile','Api\ImageProfileController@DeleteImageProfile');
    Route::apiResource('user/announces', 'Api\AnnouncesController');
    Route::post('user/update/profile/{id}','Api\UpdateProfileController@Update');
    Route::apiResource('bookmark','Api\BookmarkController');
    
    Route::post('resetpassword','Api\ResetPasswordController@ResetPassword');
});

Route::group(['middleware' => ['admin']], function () {
    Route::apiResource('admin/announces','Api\Admin_AnnouncesController');
    Route::apiResource('admin/users','Api\UsersController');
});