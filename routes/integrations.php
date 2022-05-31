<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CurrikiGo Integration API Routes - Brightcove Player, Safarimontage
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
    Route::group(['prefix' => 'brightcove'], function () {
        Route::get('{accountId}/{videoId}/{dataPlayer}/{dataEmbed}/h5p-resource-settings', 'BrightcoveController@getH5pResourceSettings');
        Route::get('{videoId}/h5p-resource-settings', 'BrightcoveController@getH5pBrightcoveResourceSettings');
    });

    Route::middleware(['auth:api', 'verified'])->group(function () {
        // Kaltura Video Integration For Curriki Interactive Video
        Route::group(['prefix' => 'kaltura'], function () {
            Route::post('get-media-entry-list', 'CurrikiInteractiveVideoIntegration\Kaltura\KalturaGeneratedAPIClientController@getMediaEntryList');
        });

        // Vimeo API For Video Integration
        Route::group(['prefix' => 'vimeo'], function () {
            Route::post('get-my-video-list', 'Integration\VimeoAPIClientController@getMyVideosList');
        });
    });
    

    // Brightcove Video Integration
    Route::group(['prefix' => 'brightcove'], function () {
        Route::get('suborganization/{suborganization}/get-bc-account-list', 'Integration\BrightcoveAPIClientController@getAccountList');        
        Route::post('get-bc-videos-list', 'Integration\BrightcoveAPIClientController@getVideosList');
    });

    // Get video direct url for interactive video
    Route::group(['prefix' => 'video'], function () {
        Route::post('get-direct-url', 'Integration\VideoDirectUrlController@getDirectUrl');
    });
});
