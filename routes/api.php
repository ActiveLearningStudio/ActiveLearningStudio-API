<?php

use Illuminate\Support\Facades\Route;

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

Route::post('register', 'Auth\AuthController@register')->name('register');
Route::post('login', 'Auth\AuthController@login')->name('login');
Route::post('forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('reset-password', 'Auth\ResetPasswordController@reset');
Route::post('verify-email', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('verify-email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::post('logout', 'Auth\AuthController@logout')->name('logout')->middleware(['auth:api', 'verified']);

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::post('subscribe', 'UserController@subscribe');
        Route::get('users/me', 'UserController@me');
        Route::apiResource('users', 'UserController');

        Route::post('projects/upload-thumb', 'ProjectController@uploadThumb');
        Route::post('projects/{project}/share-project', 'ProjectController@share');
        Route::post('projects/{project}/remove-share-project', 'ProjectController@removeShare');
        Route::apiResource('projects', 'ProjectController');

        Route::post('projects/{project}/playlists/reorder', 'PlaylistController@reorder');
        Route::apiResource('projects.playlists', 'PlaylistController');

        Route::apiResource('activities', 'ActivityController');

        Route::get('activity-types/{activityType}/items', 'ActivityTypeController@items');
        Route::apiResource('activity-types', 'ActivityTypeController');

        Route::apiResource('activity-items', 'ActivityItemController');

        Route::group(['prefix' => 'h5p'], function () {
            Route::resource('/', "H5pController");
            Route::get('settings', "H5pController@create");
            Route::get('embed/{id}', "H5pController@embed");
        });

        //CurrikiGo
        Route::group(["prefix" => "go"], function(){
            
            //LMS Settings
            Route::group(["prefix" => "lms-settings"], function(){
                Route::get('user/me', 'CurrikiGo\LmsSettingController@my');
            });
            Route::group(["prefix" => "canvas"], function(){
                Route::post('projects/{project}/playlists/{playlist}/publish', 'CurrikiGo\PublishController@playlistToCanvas');
                Route::post('projects/{project}/fetch', 'CurrikiGo\CourseController@fetchFromCanvas');
            });
            
        });
        

    });

    Route::get('error', 'ErrorController@show')->name('api/error');
});
