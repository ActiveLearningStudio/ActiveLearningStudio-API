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
Route::post('admin/login', 'Auth\AuthController@adminLogin')->name('admin.login');
Route::post('login/google', 'Auth\AuthController@loginWithGoogle');
Route::post('forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('reset-password', 'Auth\ResetPasswordController@resetPass');
Route::post('verify-email', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('verify-email/resend', 'Auth\VerificationController@resendEmail')->name('verification.resend');
Route::post('logout', 'Auth\AuthController@logout')->name('logout')->middleware(['auth:api', 'verified']);

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
    Route::get('projects/{project}/load-shared', 'ProjectController@loadShared');
    Route::get('playlists/{playlist}/load-shared', 'PlaylistController@loadShared');

    Route::get('activities/{activity}/log-view', 'MetricsController@activityLogView')->name('metrics.activity-log');
    Route::get('playlists/{playlist}/log-view', 'MetricsController@playlistLogView')->name('metrics.playlist-log');
    Route::get('projects/{project}/log-view', 'MetricsController@projectLogView')->name('metrics.project-log');

    Route::get('organization-types', 'OrganizationTypesController@index');

    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::post('subscribe', 'UserController@subscribe');
        Route::get('users/me', 'UserController@me');
        Route::post('users/update-password', 'UserController@updatePassword');
        Route::get('users/me/redeem/{offerName}', 'UserMembershipController@redeemOffer')->name('membership.redeem-offer');
        Route::apiResource('users', 'UserController')->only([
            'index', 'show', 'update', 'destroy'
        ]);;

        Route::post('projects/upload-thumb', 'ProjectController@uploadThumb');
        Route::get('projects/recent', 'ProjectController@recent');
        Route::get('projects/default', 'ProjectController@default');
        Route::get('projects/detail', 'ProjectController@detail');
        Route::get('projects/update-order', 'ProjectController@populateOrderNumber');
        Route::post('projects/reorder', 'ProjectController@reorder');
        Route::post('projects/{project}/share', 'ProjectController@share');
        Route::post('projects/{project}/clone', 'ProjectController@clone');
        Route::post('projects/{project}/remove-share', 'ProjectController@removeShare');
        Route::apiResource('projects', 'ProjectController');

        Route::post('projects/{project}/playlists/reorder', 'PlaylistController@reorder');
        Route::post('projects/{project}/playlists/{playlist}/clone', 'PlaylistController@clone');
        Route::apiResource('projects.playlists', 'PlaylistController');

        Route::post('playlists/{playlist}/activities/{activity}/clone', 'ActivityController@clone');
        Route::post('activities/upload-thumb', 'ActivityController@uploadThumb');
        Route::get('activities/{activity}/share', 'ActivityController@share');
        Route::get('activities/update-order', 'ActivityController@populateOrderNumber');
        Route::get('activities/{activity}/remove-share', 'ActivityController@removeShare');
        Route::get('activities/{activity}/detail', 'ActivityController@detail');
        Route::get('activities/{activity}/h5p', 'ActivityController@h5p');
        Route::get('activities/{activity}/h5p-resource-settings', 'ActivityController@getH5pResourceSettings');
        Route::get('activities/{activity}/h5p-resource-settings-open', 'ActivityController@getH5pResourceSettingsOpen');
        Route::apiResource('activities', 'ActivityController');

        Route::get('activity-types/{activityType}/items', 'ActivityTypeController@items');
        Route::apiResource('activity-types', 'ActivityTypeController');

        Route::apiResource('activity-items', 'ActivityItemController');

        Route::get('users/{user}/metrics', 'UserMetricsController@show')->name('metrics.user');
        Route::get('users/{user}/membership', 'UserMembershipController@show')->name('membership.show');

        Route::get('h5p/settings', 'H5pController@create');
        Route::get('h5p/activity/{activity}', 'H5pController@showByActivity');
        Route::apiResource('h5p', 'H5pController');

        Route::group(['prefix' => 'h5p'], function () {
            // H5P Ajax calls
            Route::match(['GET', 'POST'], 'ajax/libraries', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@libraries')->name('h5p.ajax.libraries');
            Route::get('ajax/single-libraries', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@singleLibrary')->name('h5p.ajax.single-libraries');
            Route::any('ajax/content-type-cache', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@contentTypeCache')->name('h5p.ajax.content-type-cache');
            Route::any('ajax/library-install', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@libraryInstall')->name('h5p.ajax.library-install');
            Route::post('ajax/library-upload', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@libraryUpload')->name('h5p.ajax.library-upload');
            Route::post('ajax/rebuild-cache', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@rebuildCache')->name('h5p.ajax.rebuild-cache');
            Route::any('ajax/filter', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@filter')->name('h5p.ajax.filter');
            Route::any('ajax/finish', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@finish')->name('h5p.ajax.finish');
            Route::any('ajax/content-user-data', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@contentUserData')->name('h5p.ajax.content-user-data');
        });

        // Elasticsearch
        Route::get('search', 'SearchController@search');
        Route::get('search/advanced', 'SearchController@advance');

        // CurrikiGo
        Route::group(['prefix' => 'go'], function () {
            // LMS Settings
            Route::group(['prefix' => 'lms-settings'], function () {
                Route::get('user/me', 'CurrikiGo\LmsSettingController@my');
            });

            Route::group(['prefix' => 'canvas'], function () {
                Route::post('projects/{project}/playlists/{playlist}/publish', 'CurrikiGo\PublishController@playlistToCanvas');
                Route::post('projects/{project}/fetch', 'CurrikiGo\CourseController@fetchFromCanvas');
            });

            Route::group(['prefix' => 'moodle'], function () {
                Route::post('projects/{project}/playlists/{playlist}/publish', 'CurrikiGo\PublishController@playlistToMoodle');
                Route::post('projects/{project}/fetch', 'CurrikiGo\CourseController@fetchFromMoodle');
            });
        });

        // Google Share
        Route::group(['prefix' => 'google-classroom'], function () {
            Route::post('access-token', 'GoogleClassroomController@saveAccessToken');
            Route::get('courses', 'GoogleClassroomController@getCourses');
            Route::post('projects/{project}/copy', 'GoogleClassroomController@copyProject');
        });
    });

    Route::get('activities/{activity}/h5p-resource-settings-shared', 'ActivityController@getH5pResourceSettingsShared');
    // H5P Activity public route
    Route::get('h5p/activity/{activity}/visibility/{visibility}', 'H5pController@showByActivity');
    // Route to support H5P Editor's core js library file upload with 'new XMLHttpRequest()'
    Route::any('h5p/ajax/files', '\Djoudi\LaravelH5p\Http\Controllers\AjaxController@files')->name('h5p.ajax.files');
    // H5P export public route for H5P toolbar and cloning
    Route::get('h5p/export/{id}', '\Djoudi\LaravelH5p\Http\Controllers\DownloadController')->name('h5p.export');
    // H5P embed
    Route::get('h5p/embed/{id}', 'H5pController@embed');
    // Public route used for LTI previews
    Route::post('go/lms/projects', 'CurrikiGo\LmsController@projects');
    // LTI Playlist
    Route::get('playlists/{playlist}/lti', 'PlaylistController@loadLti');

    Route::get('error', 'ErrorController@show')->name('api/error');

    /*********************** ADMIN PANEL ROUTES ************************/
    Route::group([
        'prefix' => 'admin',
        'as' => 'v1.admin.',
        'namespace' => 'Admin',
        'name' => 'admin.',
        'middleware' => ['auth:api', 'verified', 'admin']
    ], function () {
        // users
        Route::get('users/report/basic', 'UserController@reportBasic')->name('users.report.basic');
        Route::post('users/bulk/import', 'UserController@bulkImport')->name('users.bulk.import');
        Route::get('users/assign/starter-projects', 'UserController@assignStarterProjects')->name('users.assign.starter-projects');
        Route::apiResource('users', 'UserController');

        // projects
        Route::post('projects/indexes', 'ProjectController@updateIndexes');
        Route::get('projects/user-starters/flag', 'ProjectController@updateUserStarterFlag');
        Route::post('projects/starter/{flag}', 'ProjectController@toggleStarter');
        Route::get('projects/{project}/index', 'ProjectController@updateIndex');
        Route::get('projects/{project}/public-status', 'ProjectController@togglePublicStatus');
        Route::get('projects/{project}/load-shared', 'ProjectController@loadShared');
        Route::apiResource('projects', 'ProjectController');

        // lms-settings
        Route::apiResource('lms-settings', 'LmsSettingController');

        // activity-types
        Route::apiResource('activity-types', 'ActivityTypeController');

        // activity-items
        Route::apiResource('activity-items', 'ActivityItemController');

        // organization-types
        Route::apiResource('organization-types', 'OrganizationTypesController');
    });

    // admin public routes for downloads / uploads
    Route::get('admin/users/import/sample-file', 'Admin\UserController@downloadSampleFile')->name('users.import.sample-file');
});
