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

Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\RegisterController@Register');
Route::post('mail/message', 'Api\MailController@store');

Route::post('validation-email', 'Api\RegisterController@ValidationEmail');
Route::get('announces', 'Api\PublicAnnouncesController@announces');
Route::post('announces/search', 'Api\PublicAnnouncesController@search');
Route::get('announces/{id}', 'Api\PublicAnnouncesController@announcesByID');

Route::get('province', 'Api\DistrictsController@province');
Route::get('amphoe/{code?}', 'Api\DistrictsController@amphoe');
Route::get('district/{code?}', 'Api\DistrictsController@district');
Route::get('districts/{code?}', 'Api\DistrictsController@districts');

Route::post('token/refresh', 'Api\AuthController@refresh');  

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::post('logout', 'Api\AuthController@logout');

    Route::post('user/data', 'Api\AuthController@user');
    Route::post('user/imageprofile', 'Api\ImageProfileController@ImageProfile');
    Route::delete('user/imageprofile', 'Api\ImageProfileController@DeleteImageProfile');
    Route::apiResource('user/announces', 'Api\AnnouncesController');
    Route::post('user/announces/search', 'Api\AnnouncesController@search');

    Route::get('user/count/announces', 'Api\StatusAnnouncesController@announcesCount');
    Route::get('user/online/announces', 'Api\StatusAnnouncesController@online');
    Route::get('user/draft/announces', 'Api\StatusAnnouncesController@draft');
    Route::get('user/correct/announces', 'Api\StatusAnnouncesController@correct');

    Route::apiResource('bookmark', 'Api\BookmarkController');
    Route::get('user/bookmark', 'Api\BookmarkController@getId');
    Route::post('user/update/profile', 'Api\UpdateProfileController@Update');

    Route::apiResource('mail', 'Api\MailController');
    Route::post('mail/search', 'Api\MailController@search');
    Route::get('user/read/mail', 'Api\StatusMailController@read');
    Route::get('user/unread/mail', 'Api\StatusMailController@unread');
    Route::get('user/save/mail', 'Api\StatusMailController@save');  
    Route::get('count/mail','Api\MailReadStatusController@mailCount');
    Route::patch('mail/read/{id}', 'Api\MailReadStatusController@read');
    Route::patch('mail/unread/{id}', 'Api\MailReadStatusController@unread');
    Route::patch('mail/save/{id}', 'Api\MailReadStatusController@save');

    Route::post('resetpassword', 'Api\ResetPasswordController@ResetPassword');
});

Route::group(['middleware' => ['admin']], function () {
    Route::apiResource('admin/announces', 'Api\Admin_AnnouncesController');
    Route::apiResource('admin/users', 'Api\UsersController');
});
